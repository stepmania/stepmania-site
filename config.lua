local config = require "lapis.config"

-- Maximum file size
local body_size = "1k"

-- Path to your lua libraries (LuaRocks and OpenResty)
local lua_path  = "/root/.luarocks/share/lua/5.1/?.lua;/root/.luarocks/share/lua/5.1/?/init.lua;/usr/share/lua/5.1/?.lua;/usr/share/lua/5.1/?/init.lua;./?.lua;/usr/share/luajit-2.0.4/?.lua;/usr/local/share/lua/5.1/?.lua;/usr/local/share/lua/5.1/?/init.lua"
lua_path = lua_path .. ";./extern/?.lua;./extern/?/init.lua"
lua_path = lua_path .. ";./src/?.lua;./src/?/init.lua"

local lua_cpath = "/root/.luarocks/lib/lua/5.1/?.so;/usr/lib/lua/5.1/?.so;./?.so;/usr/local/lib/lua/5.1/?.so;/usr/lib64/lua/5.1/?.so;/usr/local/lib/lua/5.1/loadall.so"
lua_cpath = lua_cpath .. ";/usr/lib/?.so;/usr/lib64/?.so"

config("devel", {
	site_name = "[DEVEL] %s",
	port      = 2808,
	body_size = body_size,
	lua_path  = lua_path,
	lua_cpath = lua_cpath
})

config("prod", {
	code_cache = "on",
	site_name  = "%s",
	port       = 2808,
	body_size  = body_size,
	lua_path   = lua_path,
	lua_cpath  = lua_cpath
})
