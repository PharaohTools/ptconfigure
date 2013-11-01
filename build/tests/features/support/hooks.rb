# Screenshot capture

After do |scenario|
  if scenario.failed?
    name =  scenario.name.gsub(' ', '_').downcase.gsub(/[^a-z0-9_]/i, '')
    path = "../../reports/cucumber/html/screenshots/#{name}.png"
    page.driver.browser.save_screenshot path
    embed "screenshots/#{name}.png", "image/png"
  end
end