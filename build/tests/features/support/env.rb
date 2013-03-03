require 'rake'
require 'cucumber'
require 'capybara/cucumber'
require 'selenium-webdriver'
require_relative 'archiver'

Capybara.configure do |config|
  # [asset_root = String]               Where static assets are located, used by save_and_open_page
  # [app_host = String]                 The default host to use when giving a relative URL to visit
  # [run_server = Boolean]              Whether to start a Rack server for the given Rack app (Default: true)
  # [default_selector = :css/:xpath]    Methods which take a selector use the given type by default (Default: CSS)
  # [default_wait_time = Integer]       The number of seconds to wait for asynchronous processes to finish (Default: 2)
  # [ignore_hidden_elements = Boolean]  Whether to ignore hidden elements on the page (Default: false)
  # [prefer_visible_elements = Boolean] Whether to prefer visible elements over hidden elements (Default: true)
  # [automatic_reload = Boolean]        Whether to automatically reload elements as Capybara is waiting (Default: true)
  # [save_and_open_page_path = String]  Where to put pages saved through save_and_open_page (Default: Dir.pwd)
  #
  host = Proc.new do |env|
    case env.to_sym
      when :development
        ENV["APPLICATION_HOST"] || "****FEATURES URI****"
      when :test

      when :production

      else

    end

  end

  config.app_host = host.call(ENV["RACK_ENV"] || "development")
  config.default_driver = :selenium # selenium webdriver is win!
  config.default_wait_time= 5 # seconds
  config.run_server= false # PHP apps dont auto-run from ruby.
  config.default_selector = :css

end

# Run archiving utility
archive_reports
