Given /^I visit the homepage$/ do
  visit "/"
end

Then /^I should see "([^"]*)"$/ do |phrase|
  page.text.should =~ /#{phrase}/
end

Then /^I should see a "(.*?)" message$/ do |msg|
  page.text.should =~ /#{msg}/
end
