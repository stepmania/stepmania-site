local lapis = require "lapis"
local app = lapis.Application()
app:enable("etlua")
app.layout = require "views.layout"

app:get("/", function(self)
	self.base_tag = "<base href=\"/\">"
	self.site_title = "StepMania"
	self.page_title = "News"
	self.page_type  = "news"
	self.user = {
		can_edit = true
	}
	self.site = {
		analytics_id = false
	}
	self.menu = {
		{
			link = "#",
			title = "News",
			mode = "current"
		}, {
			link = "#",
			title = "Download",
			mode = "link"
		}, {
			link = "#",
			title = "Wiki",
			mode = "link"
		}, {
			link = "#",
			title = "Forums",
			mode = "link"
		}
	}
	return {
		render = "index"
	}
end)

return app
