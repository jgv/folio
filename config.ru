=begin
require "net/http"

class ProxyApp
  def call(env)
    begin
      request = Rack::Request.new(env)
      headers = {}
      env.each do |key, value|
        if key =~ /^http_(.*)/i
          headers[$1] = value
        end
      end
      http = Net::HTTP.new("localhost", 8080)
      http.start do |http|
        response = http.send_request(request.request_method, '/folio' + request.fullpath, request.body.read, headers)
        [response.code, response.to_hash, [response.body]]
      end
    rescue Errno::ECONNREFUSED
      [500, {}, ["Server is down, try $ sudo apachectl start"]]
    end
  end
end
run ProxyApp.new
=end
