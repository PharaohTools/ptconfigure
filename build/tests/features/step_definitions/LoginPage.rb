$defaultLoginFormData = {
    "email" => "damanshia@ebay.com",
    "userPass" => "password"
}

def fillLoginForm opts = {}
  opts = $defaultLoginFormData.merge opts
  fill_in "email", with: opts["email"]
  fill_in "userPass", with: opts["userPass"]
end


Given /^I visit the login page$/ do
  visit "/index.php?control=login&action=login"
end

Given /^I fill in an incorrect login form with no email address$/ do
  $inCorrectFormData = {
      "email" => "" }
  fillLoginForm $inCorrectFormData
end

Given /^I fill in an incorrect login form with no password/ do
  $inCorrectFormData = {
      "userPass" => "" }
  fillLoginForm $inCorrectFormData
end

Given /^I fill in a correct login form/ do
  fillLoginForm
end