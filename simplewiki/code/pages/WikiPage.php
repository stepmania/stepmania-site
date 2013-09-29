<?php
/**
 * A page type that provides wiki like editing functionality. The  
 * initial goals are
 * 
 * - automatically create child pages based on [[page name]] style syntax
 * - be able to edit content via a wysiwyg mechanism
 * - add links by picking pages from the tree selection control
 * - add images picking images from the tree selection control
 * 
 * @author Marcus Nyeholt <marcus@silverstripe.com.au>
 * @license BSD License (http://silverstripe.org/BSD-License)
 */
class WikiPage extends Page {

	public static $db = array(
		'EditorType'		=> "Varchar(32)",
		// Who was the last editor of the page?
		'WikiLastEditor'	=> 'Varchar(64)',
		'WikiLockExpiry'	=> 'SS_Datetime',
	);
	/**
	 * lock pages for 1 minute at a time by default
	 *
	 * This value is in seconds
	 *
	 * @var int
	 */
	public static $lock_time = 60;
	/**
	 * Set this to true in your mysite/_config.php file to force publishing
	 * as soon as you hit save. Removes the potentially awkward step of
	 * save/done/publish making pages seem to 'disappear'. Consider the 
	 * situation where a user creates a new page link (while editing a page in stage),
	 * then publishes the edited page, which takes them to the newly published
	 * page; the page they created hasn't been published, so there's now a 
	 * broken link. 
	 * 
	 *  By setting this to true, you more closely mirror the functionality of
	 *  other wikis that have save -> live 
	 * 
	 * @var boolean
	 */
	public static $auto_publish = true;
	/**
	 * Whether or not to allow public users to see the 'edit' button. If not
	 * set, the user must manually know to hit the 'edit' URL, or the assumption
	 * is that there is a separate module managing the login of users on the
	 * frontend of the website. 
	 * 
	 * @var boolean
	 */
	public static $show_edit_button = true;
	/**
	 * Whether to run the content through HTMLPurify before we display it to users
	 *
	 * @var boolean
	 */
	public static $purify_output = false;
	/**
	 * An array of plugins that allows developers to provide thirdparty field types
	 *
	 * @var array
	 */
	protected static $registered_formatters;

	/**
	 * Register a formatter
	 *
	 * @param SimpleWikiFormatter $formatter
	 * 			The formatter to register
	 */
	public static function register_formatter(SimpleWikiFormatter $formatter) {
		self::$registered_formatters[$formatter->getFormatterName()] = $formatter;
	}

	/**
	 * Before writing, convert any page links to appropriate 
	 * new, non-published, pages
	 * 
	 * @see sapphire/core/model/SiteTree#onBeforeWrite()
	 */
	protected function onBeforeWrite() {
		parent::onBeforeWrite();

		// Changes in 2.4 mean that $this->Content can now become polluted with UTF-8 HTML entitised garbage
		// we'll leave this legacy conversion in for now?
		$this->Content = str_replace('&#13;', '', $this->Content);

		$formatter = $this->getFormatter();
		$formatter->analyseSavedContent($this);

		// set a lock expiry in the past if there's not one already set
		if (!$this->WikiLockExpiry) {
			$this->WikiLockExpiry = date('Y-m-d H:i:s');
		}

		// Make sure to set the last editor to the current user
		if (Member::currentUser()) {
			$this->WikiLastEditor = Member::currentUser()->Email;
		}
	}

	/**
	 * Returns whether or not the current user can edit this page
	 *
	 * If the
	 */
	public function canEdit($member=null) {
		$can = parent::canEdit($member);

		if (!$can) {
			// see if they can via the wiki permission explicitly
			$can = Permission::check(EDIT_WIKI);
		}

		return $can;
	}

	/**
	 * Get the CMS fields
	 * @see sapphire/core/model/SiteTree#getCMSFields()
	 */
	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$options = $this->getEditorTypeOptions();
		$fields->addFieldToTab('Root.Behaviour', new OptionsetField('EditorType', _t('WikiPage.EDITORTYPE', 'Editor Type'), $options));
		
		// if we're not using the HTML editor type, we should just use a textarea edit field
		$formatter = $this->getFormatter();
		if ($formatter) {
			$formatter->updateCMSFields($fields);
		}
		
		return $fields;
	}

	public function getEditorTypeOptions() {
		$options = array();
		foreach (self::$registered_formatters as $fieldType) {
			$options[$fieldType->getFormatterName()] = $fieldType->getFormatterName();
		}
		return $options;
	}


	/**
	 * Return the editor type to use for this item. Will interrogate
	 * parents if needbe
	 * 
	 * @return String
	 */
	public function getActualEditorType() {
		if ($this->EditorType && $this->EditorType != 'Inherit') {
			return $this->EditorType;
		}

		$parent = $this->getParent();
		$editorType = 'Wiki';
		while ($parent != null && $parent instanceof WikiPage) {
			if ($parent->EditorType && $parent->EditorType != 'Inherit') {
				return $parent->EditorType;
			}
			$parent = $parent->getParent();
		}

		return 'Wiki';
	}

	/**
	 * Gets the formatter for a given type. If none specified, gets the current formatter
	 * 
	 * @return SimpleWikiFormatter
	 */
	public function getFormatter($formatter=null) {
		if (!$formatter) {
			$formatter = $this->getActualEditorType();
		}

		if (!isset(self::$registered_formatters[$formatter])) {
			throw new Exception("Formatter $formatter does not exist");
		}

		return self::$registered_formatters[$formatter];
	}

	/**
	 * Retrieves the page's content, passed through any necessary parsing
	 * eg Wiki based content
	 * 
	 * @return String
	 */
	public function ParsedContent() {
		$formatter = $this->getFormatter();
		$content = $formatter->formatContent($this);

		// purify the output - we don't want people breaking pages if we set purify=true
		if (self::$purify_output) {
			include_once SIMPLEWIKI_DIR . '/thirdparty/htmlpurifier-4.0.0-lite/library/HTMLPurifier.auto.php';
			$purifier = new HTMLPurifier();
			$content = $purifier->purify($content);
			$content = preg_replace_callback('/\%5B(.*?)\%5D/', array($this, 'reformatShortcodes'), $content);
		}

		return $content;
	}

	/**
	 * Reformats shortcodes after being run through htmlpurifier
	 *
	 * @param array $matches
	 */
	public function reformatShortcodes($matches) {
		$val = urldecode($matches[1]);
		return '[' . $val . ']';
	}

	/**
	 * Get the root of the wiki that this wiki page exists in
	 *
	 * @return WikiPage
	 */
	public function getWikiRoot() {
		$current = $this;
		$parent = $current->Parent();
		while ($parent instanceof WikiPage) {
			$current = $parent;
			$parent = $current->Parent();
		}
		return $current;
	}

	/**
	 * Lock the page for the current user
	 *
	 * @param Member $member
	 * 			The user to lock the page for
	 */
	public function lock($member = null) {
		if (!$member) {
			$member = Member::currentUser();
		}

		// set the updated lock expiry based on now + lock timeout
		$this->WikiLastEditor = $member->Email;
		$this->WikiLockExpiry = date('Y-m-d H:i:s', time() + WikiPage::$lock_time);

		// save it with us as the editor
		$this->write();
	}
	
	
	/*
	 * Form for the insert link dialog box
	 */
	public function LinkPickerForm()
	{
		$fields = new FieldSet(
			new OptionsetField(
		    	$name = "Type",
		    	$title = "Link to a",
		    	$source = array(
		       		"page" => "Page on this site",
		       		"file" => "File or image on this site",
		       		"external" => "External URL"
		   	 	),
		   		$value = "page"
		 	),
			new TextField('Link', 'Search by page title'),
			new TextField('Title', 'Title')
			
		);
		
		$actions = new FieldSet(
            //new FormAction('Submit', 'Submit')
        );
        
		return new Form($this, "LinkPickerForm", $fields, $actions);
	}
	
	
	/*
	 * Form for the insert image dialog box
	 */
	public function ImagePickerForm()
	{
		$fields = new FieldSet(
			new OptionsetField(
		    	$name = "Type",
		    	$title = "Image source",
		    	$source = array(
		    		"new" => "Upload from your computer",
		       		"existing" => "Existing image in the file system"
		   	 	),
		   		$value = "new"
		 	),
			$ff = new FileField('NewImage', 'Upload image'),
			new TextField('ExistingImage', 'Search by filename'),
			new TextField('Title', 'Title'),
			new LiteralField('UploadingIcon', '<div id="uploadingIcon" style="display:none"><img src="simplewiki/images/loading.gif" /></div>')
		);
		
		$ff->getValidator()->setAllowedMaxFileSize(3145728); // 3mb
		
		$actions = new FieldSet(
            //new FormAction('Submit', 'Submit')
        );
        
		return new Form($this, "ImagePickerForm", $fields, $actions);
	}

}

class WikiPage_Controller extends Page_Controller implements PermissionProvider {

	static $allowed_actions = array(
		'linkselector',
		'edit',
		'StatusForm',
		'save',
		'done',
		'publish',
		'cancel',
		'revert',
		'startediting',
		'EditForm',
		'LinkSelectForm',
		'objectdetails',
		'CreatePageForm',
		'delete',
		'addpage',
		'updatelock',
		'livepreview',
		'imagepicker',
		'linkpicker',
		'linklist',
		'imageupload'
	);

	public function init() {
		parent::init();
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript('simplewiki/javascript/simplewiki.js');
		Requirements::css('simplewiki/css/simplewiki.css');
	}

	/**
	 * Define some permissions used for editing wiki pages
	 *
	 * @return array
	 */
	public function providePermissions() {
		return array(
			EDIT_WIKI => array(
				'name' => _t('WikiPage.PERM_EDIT', 'Edit Wiki Pages'),
				'category' => _t('WikiPage.WIKI_CATEGORY', 'Wiki'),
				'sort' => -100,
				'help' => _t('WikiPage.PERM_EDIT_HELP', 'Allows users to edit wiki pages')
			),
			MANAGE_WIKI_PAGES => array(
				'name' => _t('WikiPage.MANAGE_PAGES', 'Manage Wiki pages'),
				'category' => _t('WikiPage.WIKI_CATEGORY', 'Wiki'),
				'sort' => -100,
				'help' => _t('WikiPage.CREATE_PAGES_HELP', 'Display controls that allow users to create and delete aribtrary pages from the Wiki editing UI')
			),
		);
	}

	/**
	 * The form we're editing with
	 * 
	 * @var Form
	 */
	protected $form;

	/**
	 * Action handler for editing this wiki page
	 * 
	 * Creates a form that's used for editing the page's content, 
	 * as well as adding in a couple of additional toolbar actions
	 * for adding a simple link and a simple image
	 */
	public function edit() {
		HtmlEditorField::include_js();
//		Requirements::javascript('simplewiki/javascript/sslinks/editor_plugin_src.js');

		$existing = $this->getEditingLocks($this->owner, true);
		// oops, we've somehow got here even though we shouldn't have
		if ($existing && $existing['user'] != Member::currentUser()->Email) {
			Director::redirect($this->owner->Link());
			return;
		}

		if (!$this->owner->canEdit()) {
			return Security::permissionFailure($this);
		}

		$this->form = $this->EditForm();

		// check who's editing and whether or not we should bail out
		return $this->renderWith(array('WikiPage', 'Page'));
	}

	/**
	 * Creates the form used for editing the page's content
	 * 
	 * @return Form
	 */
	public function EditForm() {
		// make sure to load fresh from db
		$record = DataObject::get_by_id('WikiPage', $this->data()->ID);
		$formatter = $record->getFormatter();

		$editorField = $formatter->getEditingField($record);
		$helpLink = $formatter->getHelpUrl();


		$fields = new FieldSet(
						new LiteralField('Preview', '<div data-url="'.$this->Link('livepreview').'" id="editorPreview"></div>'),
						new LiteralField('DialogContent', '<div id="dialogContent" style="display:none;"></div>'),
						$editorField,
						new DropdownField('EditorType', _t('WikiPage.EDITORTYPE', 'Editor Type'), $this->data()->getEditorTypeOptions()),
						new HiddenField('LockUpdate', '', $this->owner->Link('updatelock')),
						new HiddenField('LockLength', '', WikiPage::$lock_time - 10)
		);
		

		if ($helpLink) {
			$fields->push(new LiteralField('HelpLink', '<a target="_blank" href="' . $helpLink . '">' . _t('WikiPage.EDITOR_HELP_LINK', 'Editor Help') . '</a>'));
		}

		$actions = null;
		if (!WikiPage::$auto_publish) {
			$actions = new FieldSet(
							new FormAction('save', _t('WikiPage.SAVE', 'Save')),
							new FormAction('done', _t('WikiPage.DONE', 'Done (Draft)')),
							new FormAction('publish', _t('WikiPage.PUBLISH', 'Publish'))
			);
		} else {
			$actions = new FieldSet(
							new FormAction('save', _t('WikiPage.SAVE', 'Save')),
							new FormAction('publish', _t('WikiPage.FINISHED', 'Finished'))
			);
		}

		$actions->push(new FormAction('cancel', _t('WikiPage.CANCEL_EDIT', 'Cancel')));
		$actions->push(new FormAction('revert', _t('WikiPage.REVERT_EDIT', 'Revert')));

		if (Permission::check(MANAGE_WIKI_PAGES)) {
			$actions->push(new FormAction('addpage_t', _t('WikiPage.ADD_PAGE', 'New Page')));
			$actions->push(new FormAction('delete', _t('WikiPage.DELETE_PAGE', 'Delete Page')));
		}

		$form = new Form($this, "EditForm", $fields, $actions);
		$form->loadDataFrom($record);
		return $form;
	}

	/**
	 * Returns the form used to create new pages. If the current form is not set
	 * (ie the user is NOT currently editing), then we just return null.
	 *
	 * @return Form
	 */
	public function CreatePageForm() {
		$createOptions = array(
			'child' => 'As a child of the selected page',
			'sibling' => 'As a sibling of the selected page',
		);

		$pageTree = new TreeDropdownField('CreateContext', _t('WikiPage.CREATE_CONTEXT', 'Select an existing page'), 'WikiPage');
		$pageTree->setValue($this->ID);
		$pageTree->setTreeBaseID($this->data()->getWikiRoot()->ID);
		$fields = new FieldSet(
						new TextField('NewPageName', _t('WikiPage.NEW_PAGE_NAME', 'New Page Name')),
						$pageTree,
						new OptionsetField('CreateType', _t('WikiPage.CREATE_OPTIONS', 'and create the new page '), $createOptions, 'child')
		);

		$actions = new FieldSet(new FormAction('addpage', _t('WikiPage.ADD_PAGE', 'Create')));

		return new Form($this, 'CreatePageForm', $fields, $actions);
	}

	/**
	 * basic action that the user can use to just quit editing
	 * 
	 */
	public function cancel() {
		Director::redirect($this->owner->Link() . '?stage=Stage');
	}

	/**
	 * Option for the user to revert the changes made since it was last published
	 */
	public function revert() {
		if ($this->owner->IsModifiedOnStage) {
			$this->owner->doRevertToLive();
		}
		Director::redirect($this->owner->Link() . '?stage=Live');
	}

	/**
	 * Deletes the current page and returns the user to the parent
	 * of the now deleted page.
	 *
	 */
	public function delete() {
		$page = $this->owner;
		/* @var $page Page */
		if ($page) {
			$parent = $page->Parent();
			$ID = $page->ID;

			$page->deleteFromStage('Live');

			// only fully delete if we're autopublishing stuff.. a bit counter
			// intuitive, but works pretty well
			if (WikiPage::$auto_publish) {
				$page->ID = $ID;
				$page->deleteFromStage('Stage');
			}

			Director::redirect($parent->Link());
			return;
		}

		throw new Exception("Invalid request");
	}

	/**
	 * Creates an entirely new page as a child of the current page, or
	 * 'after' a selected page.
	 */
	public function addpage($args) {
		if (!Permission::check(MANAGE_WIKI_PAGES)) {
			return Security::permissionFailure($this);
		}

		$pageName = trim($args['NewPageName']);
		$createType = $args['CreateType'] ? $args['CreateType'] : 'child';
		if (!strlen($pageName)) {
			throw new Exception("Invalid page name");
		}

		$createContext = $this->owner;

		if ($args['CreateContext']) {
			$createContext = DataObject::get_by_id('WikiPage', $args['CreateContext']);
		}

		if (!$createContext instanceof WikiPage) {
			throw new Exception("You must select an existing wiki page.");
		}

		// now see whether to add the new page above, below or as a child
		$page = new WikiPage();
		$page->Title = $pageName;
		$page->MenuTitle = $pageName;

		switch ($createType) {
			case 'sibling': {
					$page->ParentID = $createContext->ParentID;
					break;
				}
			case 'child':
			default: {
					$page->ParentID = $createContext->ID;
					break;
				}
		}

		$page->write();

		// publish if we're on autopublish
		if (WikiPage::$auto_publish) {
			$page->doPublish();
		}

		Director::redirect($page->Link('edit') . '?stage=Stage');
	}

	/**
	 * 
	 * @param WikiPage $page
	 * @param array $data
	 * @return WikiPage
	 */
	protected function savePage($page, $form = null, $stage = 'Stage') {
		// save stuff then reuse the edit action
		if ($form) {
			$form->saveInto($page);
		}
		$page->Status = ($page->Status == "New page" || $page->Status == "Saved (new)") ? "Saved (new)" : "Saved (update)";
		$page->write($stage);
	}

	/**
	 * Save the submitted data
	 * 
	 * @return 
	 */
	public function save($data, $form) {
		if (!$this->owner->canEdit()) {
			return Security::permissionFailure($this);
		}

		$existing = $this->getEditingLocks($this->owner, true);
		// oops, we've somehow got here even though we shouldn't have
		if ($existing && $existing['user'] != Member::currentUser()->Email) {
			return "Someone somehow locked it while you were gone, this shouldn't happen like this :(";
		}

		$this->savePage($this->owner, $form);
		if (WikiPage::$auto_publish) {
			// do publish
			$this->owner->doPublish();
		}

		Director::redirect($this->owner->Link('edit') . '?stage=Stage');
	}

	/**
	 * Complete editing and publish the data
	 * 
	 * @param mixed $data
	 * @param Form $form
	 */
	public function done($data, $form) {
		if (!$this->owner->canEdit()) {
			return Security::permissionFailure($this);
		}
		// save stuff then reuse the edit action
		$this->savePage($this->owner, $form);

		Director::redirect($this->owner->Link() . '?stage=Stage');
	}

	/**
	 * Complete editing and publish the data
	 * 
	 * @param mixed $data
	 * @param Form $form
	 */
	public function publish($data, $form) {
		if (!$this->owner->canEdit()) {
			return Security::permissionFailure($this);
		}
		// save stuff then reuse the edit action
		$this->savePage($this->owner, $form);
		$this->owner->doPublish();

		// Make sure we're on the live content now
		Versioned::reading_stage('Live');

		// and go 
		Director::redirect($this->owner->Link() . '?stage=Live');
	}

	/**
	 * We only want to output content if we're not in edit mode
	 * at all
	 * 
	 * @return String
	 */
	public function Content() {
		if ($this->form) {
			return '';
		}

		return $this->owner->ParsedContent(); //XML_val('Content');
	}

	/**
	 * Return the form to the user if it exists, otherwise some information
	 * about who is currently editing
	 * 
	 * @return Form
	 */
	public function Form() {
		// The editing form hasn't been put in place by the 'edit' action
		// so lets just show the status form
		$append = '';
		if (!$this->form) {
			if (WikiPage::$show_edit_button || $this->owner->canEdit()) {
				// create the information form 
				$this->form = $this->StatusForm();
			}
		} else {
			// if we have got an editing form, then we'll add a New Page
			// form if we have permissions to do so
			if (Permission::check(MANAGE_WIKI_PAGES)) {
				$append = $this->CreatePageForm()->forTemplate();
			}
		}

		return $this->form->forTemplate() . $append;
	}

	/**
	 * Gets the status form that is used by users to trigger the editing mode
	 * if they have the relevant access to it. 
	 * 
	 * @return Form
	 */
	public function StatusForm() {
		$existing = $this->getEditingLocks($this->owner);

		if ($existing && $existing['user'] != Member::currentUser()->Email) {
			$fields = new FieldSet(
							new ReadonlyField('ExistingEditor', '', _t('WikiPage.EXISTINGEDITOR', 'This page is currently locked for editing by ' . $existing['user'] . ' until ' . $existing['expires']))
			);
			$actions = new FieldSet();
		} else {
			$fields = new FieldSet();
			$actions = new FieldSet(
							new FormAction('startediting', _t('WikiPage.STARTEDIT', 'Edit Page'))
			);
		}

		return new Form($this, 'StatusForm', $fields, $actions);
	}

	/**
	 * Updates the lock timeout for the given object
	 * 
	 * @param <type> $data
	 */
	public function updatelock($data) {
		if ($this->owner->ID && $this->owner->canEdit()) {
			$lock = $this->getEditingLocks($this->owner, true);
			$response = new stdClass();
			$response->status = 1;
			if ($lock != null && $lock['user'] != Member::currentUser()->Email) {
				// someone else has stolen it !
				$response->status = 0;
				$response->message = _t('WikiPage.LOCK_STOLEN', "Another user (" . $lock['user'] . ") has forcefully taken this lock");
			}
			return Convert::raw2json($response);
		}
	}

	/**
	 * Lock the page for editing
	 * 
	 * @param SiteTree $page
	 * 			The page being edited
	 * @param boolean $doLock 
	 * 			Whether to actually lock the page for ourselves
	 * @return array
	 * 			The names of any existing editors
	 */
	protected function getEditingLocks($page, $doLock=false) {
		$currentStage = Versioned::current_stage();

		Versioned::reading_stage('Stage');

		$filter = array(
			'WikiPage.ID =' => $page->ID,
			'WikiLockExpiry > ' => date('Y-m-d H:i:s'),
		);

		$filter = singleton('SimpleWikiUtils')->quote($filter);

		$user = Member::currentUser();
		$currentLock = DataObject::get_one('WikiPage', $filter);

		$lock = null;

		if ($currentLock && $currentLock->ID) {
			// if there's a current lock in place, lets return that value
			$lock = array(
				'user' => $currentLock->WikiLastEditor,
				'expires' => $currentLock->WikiLockExpiry,
			);
		}

		// If we're trying to take the lock, make sure that a) there's no existing
		// lock or b) we currently hold the lock
		if ($doLock && ($currentLock == null || !$currentLock->ID || $currentLock->WikiLastEditor == $user->Email)) {
			$page->lock();
		}

		Versioned::reading_stage($currentStage);

		return $lock;
	}

	/**
	 * Called to start editing this page
	 * 
	 */
	public function startediting() {
		Director::redirect($this->owner->Link('edit') . '?stage=Stage');
	}

	/**
	 * Show the link selector
	 * 
	 * @return String
	 */
	public function linkselector() {
		return $this->renderWith(array('LinkSelectDialog'));
	}

	/**
	 * What kind of linking is the link selection form doing
	 * @return unknown_type
	 */
	public function LinkingType() {
		return isset($_GET['type']) ? $_GET['type'] : 'href';
	}

	/**
	 * 
	 * @return Form
	 */
	public function LinkSelectForm() {
		$type = isset($_GET['type']) ? $_GET['type'] : 'href';

		$fields = new FieldSet(
						new TreeDropdownField('TargetPage', _t('WikiPage.TARGETPAGE', 'Select Page'), 'SiteTree'),
						new TreeDropdownField('TargetFile', _t('WikiPage.TARGETIMAGE', 'Select Image'), 'File')
		);

		$actions = new FieldSet(
						new FormAction('insert', _t('WikiPage.INSERTLINK', 'Insert'))
		);

		return new Form($this, 'LinkSelectForm', $fields, $actions);
	}

	/**
	 * Retrieves information about a selected image for the frontend
	 * image insertion tool - hacky for now, ideally need to pull through the
	 * backend ImageForm
	 * 
	 * @return string
	 */
	public function objectdetails() {
		$response = new stdClass;
		if (isset($_GET['ID'])) {
			$type = null;
			if (isset($_GET['type'])) {
				$type = $_GET['type'] == 'href' ? 'SiteTree' : 'File';
			} else {
				$type = 'SiteTree';
			}

			$object = DataObject::get_by_id($type, $_GET['ID']);

			$response->Title = $object->Title;
			$response->Link = $object->Link();

			if ($object instanceof Image) {
				$response->Name = $object->Name;
				$response->Filename = $object->Filename;
				$response->width = $object->getWidth();
				$response->height = $object->getHeight();
			}

			$response->error = 0;
		} else {
			$response->error = 1;
			$response->message = "Invalid image ID";
		}
		echo json_encode($response);
	}
	
	
	/*
	 * returns a formatted version of the users content field for preview
	 */
	public function livepreview(){
		$content = $_POST['content'];
		if($formatter = $this->data()->getFormatter()){
			$content = $formatter->formatRaw($content);
		}
		return $content;
	}
	
	
	/*
	 * returns the image picker form in template for dialog window
	 */
	public function imagepicker(){
		return $this->renderWith('ImagePickerDialog');
	}
	
	
	/*
	 * returns the link picker form in template for dialog window
	 */
	public function linkpicker(){
		return $this->renderWith(array('LinkPickerDialog'));
	}
	
	
	/*
	 * gets a list of files or pages for the dialogs autocomplete field
	 */
	public function linklist(){
		$term = trim(Convert::raw2sql($this->request->getVar('term')));
		$type = Convert::raw2sql($this->request->getVar('type'));
		
		if($type == 'file' || $type == 'image'){
			$filter ="Title LIKE '%$term%'";
			if($type == 'image'){
				$filter .= " AND ClassName = 'Image'";
			}		
			if($files = DataObject::get('File', $filter, $sort='Title DESC', $join='', $limit='')){
				//die($files->Count())
				$this->response->addHeader('Content-type', 'application/json');
				$return = array();
				foreach ($files as $file){
					if($file->ClassName == 'Image'){
						if($file->CroppedImage(20,20)){
							$label = $file->CroppedImage(20,20)->forTemplate() . " " . $file->Title;
						}else{
							$label = "<img src='{$file->Link()}' height='20' width = '20'/> " . $file->Title;
						}
					}else{
						$label = "<img src='{$file->Icon()}' height='20' width = '20'/> " . $file->Title;
					}
					$return [] = array(
						'ID' => $file->ID,
						'Title' => $file->Title,
						'Label' => $label,
						'Link' => $file->Link()
					); 
				}
				return Convert::raw2json($return);
			}	
		}elseif($type == 'page'){
			if($pages = DataObject::get('SiteTree', $filter ="Title LIKE '%$term%'", $sort='Title DESC', $join='', $limit='')){
				$this->response->addHeader('Content-type', 'application/json');
				$return = array();
				foreach ($pages as $page){
					$return [] = array(
						'ID' => $page->ID,
						'Label' => $page->Title,
						'Title' => $page->Title,
						'Link' => $page->Link()
					); 
				}
				return Convert::raw2json($return);
			}
		}
		
	}
	
	/*
	 * handles the upload of an image via ajax in the insert image dialog
	 */
	public function imageupload(){
		if($tempfile = $_FILES['NewImage']){
			
			// validate //
			
			$allowed = array('jpg', 'jpeg', 'gif', 'png', 'ico');
			$ext = end(explode('.', $tempfile['name']));
			if(!in_array(strtolower($ext), $allowed)){
				$return = array(
					'error' => 1,
					'text' => "Your image must be in jpg, gif or png format"
				);
				return Convert::raw2json($return);
			}
			
			$maxsize = $_POST['MAX_FILE_SIZE'];
			if($tempfile['size'] > $maxsize){
				$size = number_format($maxsize / 1024 / 1024, 2) . 'MB'; 
				$return = array(
					'error' => 1,
					'text' => "Your image must be smaller than $size"
				);
				return Convert::raw2json($return);
			}
			
			// upload //
			
			$upload	= new Upload;
			$file = new Image();
			$upload->loadIntoFile($tempfile, $file);
			if($upload->isError()) 
				return false;
			$file = $upload->getFile();
			$return =  array(
				'link' => $file->Link()
			);
			return Convert::raw2json($return);
		
		}else{
		 	// no file to upload
			return false;
		}
	}

}

?>