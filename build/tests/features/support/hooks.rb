# Screenshot capture

After do |scenario|
  if scenario.failed?
    name =  scenario.name.gsub(' ', '_').downcase.gsub(/[^a-z0-9_]/i, '')
    path = "./build/reports/cucumber/screenshots/#{name}.png"
    page.driver.browser.save_screenshot path
    embed "screenshots/#{name}.png", "image/png"
  end
end