Given /^I visit the registration page$/ do
  visit "/register"
end
Then /^I should get redirected to the contact details page$/ do
  page.current_path.should =~ /register\/(.+)\/contact-details/
end