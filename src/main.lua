local lapis = require "lapis"
local app = lapis.Application()
app:enable("etlua")
app.layout = require "views.layout"

local function prepare_page(self, app)
	self.base_tag = "<base href=\"/\">"
	self.site_title = "StepMania"
	local function last_mod(filename)
		local lfs = require "lfs"
		local full = "./static/" .. filename
		local stat = lfs.attributes(full)
		return stat.modification
	end
	self.dodge_cache = function(_, filename)
		return string.format("/static/%s?%s", filename, last_mod(filename))
	end
	self.user = {
		can_edit = true
	}
	self.site = {
		analytics_id = false
	}
	self.menu = {
		{
			link = "/",
			title = "News",
			mode = "link"
		}, {
			link = "/download",
			title = "Download",
			mode = "link"
		}, {
			link = "/scores",
			title = "Scores",
			mode = "link"
		}, {
			link = "/wiki",
			title = "Wiki",
			mode = "link"
		}, {
			link = "/forums",
			title = "Forums",
			mode = "link"
		}
	}
	for _, item in ipairs(self.menu) do
		if self.req.parsed_url.path == item.link then
			item.mode = "current"
		end
	end
end

app:match("download", "/download(/:mode)", function(self)
	prepare_page(self, app)
	local beta = self.params.mode == "beta"
	if beta then
		self.page_title = "Download StepMania (beta)"
	else
		self.page_title = "Download StepMania"
	end
	self.page_type = "download"

	-- https://api.github.com/repos/stepmania/stepmania/releases
	local f = io.open("static/stepmania-releases.json", "r")
	if not f then
		return "<div class=\"pad\"><div class=\"message bad\">the release json has gone missing, tell shakesoda</div></div>"
	end

	local data = f:read("*a")
	local json_decode = require "lapis.util".from_json
	local decoded = json_decode(data)

	-- special case: show the entire DL list
	if self.params.mode == "all" then
		self.page_title = "All StepMania Versions"
		table.sort(decoded, function(a, b)
			return a.published_at > b.published_at
		end)
		self.all_downloads = decoded
		return {
			render = "download-list"
		}
	end

	local function convert_mime(mime)
		local mimes = {
			["application/octet-stream"]        = "Mac",
			["application/x-apple-diskimage"]   = "Mac",
			["application/x-ms-dos-executable"] = "Windows",
			["application/x-msdownload"]        = "Windows"
		}
		return mimes[mime] or "Linux"
	end

	local function munge(name)
		return name:lower():gsub(" ", "-")
	end

	-- filter out inappopriate releases.
	for i=#decoded, 1, -1 do
		-- beta page filters stable out...
		if beta then
			if not decoded[i].prerelease then
				table.remove(decoded, i)
			end
		else
			-- ...and normal filters prereleases
			if decoded[i].prerelease then
				table.remove(decoded, i)
			end
		end
	end

	-- I've run into the release json being out of order, so rely on date.
	-- we can't rely on version number, due to StepMania's long history of
	-- non-linearity. It's significantly my fault, so I apologize. -ss
	if #decoded > 0 then
		table.sort(decoded, function(a, b)
			return a.published_at > b.published_at
		end)
		local info = decoded[1]
		for _, asset in ipairs(info.assets) do
			asset.platform = convert_mime(asset.content_type)
			asset.icon     = munge(asset.platform)
		end
		self.download_info = info
	end

	return {
		render = "download"
	}
end)

app:get("/profile", function(self)
	prepare_page(self, app)
	self.page_title = "Profile"
	self.page_type = "profile"
	return {
		render = "index"
	}
end)

local function clock_fmt(seconds)
	if seconds <= 0 then
		return "00:00:00";
	end
	local hours =("%02.f"):format(math.floor(seconds/3600));
	local mins = ("%02.f"):format(math.floor(seconds/60-(hours*60)));
	local secs = ("%02.f"):format(math.floor(seconds-hours*3600-mins*60));
	if seconds >= 3600 then
		return ("%s:%s:%s"):format(hours, mins, secs)
	end
	return ("%s:%s"):format(mins, secs)
end

app:get("/scores", function(self)
	prepare_page(self, app)
	self.page_title = "Scores"
	self.page_type = "scores"
	local mirror_force = {
		title = "Mirror Force",
		artist = "Kyle Snyder",
		difficulty = "Beginner",
		style = "dance-double",
		length = 134.4,
		steps = 573,
		clear_rate = 0.125
	}
	self.format_time = function(_, seconds)
		return clock_fmt(seconds)
	end
	self.scores = {
		{
			user = { name = "shakesoda" },
			song = mirror_force,
			date = "2008-02-11",
			stats = { grade = "AAA", w1 = 999, w2 = 99, w3 = 0, w4 = 0, w5 = 0, ok = 555, ng = 0, score = 100000 }
		},
		{
			user = { name = "cube" },
			song = mirror_force,
			date = "2016-10-24",
			stats = { grade = "AA", w1 = 998, w2 = 99, w3 = 1, w4 = 0, w5 = 0, ok = 555, ng = 0, score = 100000 }
		},
		{
			user = { name = "not freem" },
			song = mirror_force,
			date = "2010-01-10",
			stats = { grade = "Q", w1 = 9001, w2 = 1, w3 = 2, w4 = 9, w5 = 0, ok = 1120, ng = 573, score = 100000 }
		}
	}
	return {
		render = "scores"
	}
end)

app:get("/forums", function(self)
	prepare_page(self, app)
	self.page_title = "Forums"
	self.page_type = "forums"
	return {
		render = "index"
	}
end)

app:get("/wiki", function(self)
	prepare_page(self, app)
	self.page_title = "Wiki"
	self.page_type = "wiki"
	-- todo: load content from github wiki
	return {
		render = "index"
	}
end)

app:get("/", function(self)
	prepare_page(self, app)
	self.page_title = "News"
	self.page_type  = "news"
	return {
		render = "index"
	}
end)

return app
