-- Should return string
function onReceive(data)
	local message = data.data
	return message
end

-- Should return json
function beforeEncode(data)
	local message = data.data
	return message
end