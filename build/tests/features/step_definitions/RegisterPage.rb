randomHash = (0...4).map{65.+(rand(26)).chr}.join
randomEmail = "tsst"+randomHash+"@mail.com".to_str

$defaultRegFormData = {
    "userName" => "King Dave",
    "email" => randomEmail,
    #"email" => "davey@mail.com",
    "userPass" => "password",
    "userPass2" => "password"
}

def fillRegisterForm opts = {}
  opts = $defaultRegFormData.merge opts

  puts opts["email"]

  fill_in "userName", with: opts["userName"]
  fill_in "email", with: opts["email"]
  fill_in "userPass", with: opts["userPass"]
  fill_in "userPass2", with: opts["userPass2"]
end

Given /^I visit the register page$/ do
  visit "/index.php?control=register&action=register"
end

Given /^I fill in an incorrect register form with no username$/ do
  $inCorrectFormData = {
      "userName" => "" }
  fillRegisterForm $inCorrectFormData
end

Given /^I fill in an incorrect register form with no email address$/ do
  $inCorrectFormData = {
      "email" => "" }
  fillRegisterForm $inCorrectFormData
end

Given /^I fill in an incorrect register form with no password/ do
  $inCorrectFormData = {
      "userPass" => "" }
  fillRegisterForm $inCorrectFormData
end

Given /^I fill in an incorrect register form with no repeat password/ do
  $inCorrectFormData = {
      "userPass2" => "" }
  fillRegisterForm $inCorrectFormData
end

Given /^I fill in a correct register form/ do
  fillRegisterForm
end