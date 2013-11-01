Given /^I visit the homepage$/ do
  visit "/"
end

Then /^I should see "([^"]*)"$/ do |phrase|
  page.text.should =~ /#{phrase}/
end

Then /^I should see a "(.*?)" message$/ do |msg|
  page.text.should =~ /#{msg}/
end

Then /^I should see the field "([^"]*)"$/ do |phrase|
  find('#'+phrase)
end

Given /^I Submit the Form$/ do
  click_button("submit");
end