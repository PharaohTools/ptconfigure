def fill_registration_form name, email, phone, companyName, companyWebsite
  fill_in "ebay_registrationbundle_prospecttype_contactName", with: name
  fill_in "ebay_registrationbundle_prospecttype_contactEmail", with: email
  fill_in "ebay_registrationbundle_prospecttype_contactPhoneNumber", with: phone
  fill_in "ebay_registrationbundle_prospecttype_companyName", with: companyName
  fill_in "ebay_registrationbundle_prospecttype_companyWebsite", with: companyWebsite
end

Given /^I visit the registration form$/ do
  visit "/register-your-interest"
end

Then /^I should be redirected to 'registration complete' page$/ do
  page.current_path.should == '/registration-complete'
end

When /^I submit my registration$/ do
  fill_registration_form("name", "email@domain.com", "07736483284329", "Ebay Europe inc", "")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end

Then /^I should see some an invalid email error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_prospecttype']/div[2]/ul/li")
end

When /^I submit an invalid registration with an invalid telephone number$/ do
  fill_registration_form("name", "email@domain.com", "abcd", "Ebay Europe inc", "http://www.ebay.co.uk")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end

Then /^I should see some an invalid telephone number error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_prospecttype']/div[3]/ul/li")
end

Then /^I should see some a interest registration explanation text$/ do
  find(:accelerateGrowthRegisterInterestExplanationText)
end

When /^I submit an invalid registration without telephone number$/ do
  fill_registration_form("name", "email@domain.com", "", "Ebay Europe inc", "http://www.ebay.co.uk")
  page.execute_script("$('#ebay_registrationbundle_prospecttype_contactPhoneNumber').removeAttr('required')")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end

When /^I submit an invalid registration with an already existing email address$/ do
  fill_registration_form("name", "email@domain.com", "07736483284329", "Ebay Europe inc", "http://www.ebay.co.uk")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
  visit "/register-your-interest"
  fill_registration_form("name", "email@domain.com", "07736483284329", "Ebay Europe inc", "http://www.ebay.co.uk")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end

Then /^I should see an already registered error message$/ do
  page.text.should =~ /epd.acceleratedGrowth.interestRegistrationForm.emailAlreadyExists/
end

When /^I submit an invalid registration with an invalidly structured email address$/ do
  page.execute_script("
      var originalBtn = $(\"#ebay_registrationbundle_prospecttype_contactEmail\");
      var newBtn = originalBtn.clone();
      newBtn.attr(\"type\", \"text\");
      newBtn.insertBefore(originalBtn);
      originalBtn.remove();
      newBtn.attr(\"id\", \"ebay_registrationbundle_prospecttype_contactEmail\");
  ")
  fill_registration_form("name", "...notvalidemail@com", "07736483284329", "Ebay Europe inc", "http://www.ebay.co.uk")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end

Then /^I should see an invalid email error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_prospecttype']/div[2]/ul/li")
end



When /^I submit an invalid registration with an empty email address$/ do
  page.execute_script("
      var originalBtn = $(\"#ebay_registrationbundle_prospecttype_contactEmail\");
      var newBtn = originalBtn.clone();
      newBtn.attr(\"type\", \"text\");
      newBtn.insertBefore(originalBtn);
      originalBtn.remove();
      newBtn.attr(\"id\", \"ebay_registrationbundle_prospecttype_contactEmail\");
  ")
  fill_registration_form("name", "", "07736483284329", "Ebay Europe inc", "http://www.ebay.co.uk")
  page.execute_script("$('#ebay_registrationbundle_prospecttype_contactEmail').removeAttr('required')")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end

When /^I submit an invalid registration with an empty name$/ do
  page.execute_script("$('#ebay_registrationbundle_prospecttype_contactName').removeAttr('required')")
  fill_registration_form("", "damanshia@ebay.com", "07736483284329", "Ebay Europe inc", "http://www.ebay.co.uk")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end

Then /^I should see an invalid registration name error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_prospecttype']/div[1]/ul/li")
end

When /^I submit an invalid registration with an empty company name$/ do
  page.execute_script("$('#ebay_registrationbundle_prospecttype_companyName').removeAttr('required')")
  fill_registration_form("name", "test@test.com", "07736483284329", "", "http://www.ebay.co.uk")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end


Then /^I should see an empty registration 'company name' error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_prospecttype']/div[4]/ul/li")
end

When /^I submit an invalid registration with an invalid company web address$/ do
  page.execute_script("
      var originalBtn = $(\"#ebay_registrationbundle_prospecttype_companyWebsite\");
      var newBtn = originalBtn.clone();
      newBtn.attr(\"type\", \"text\");
      newBtn.insertBefore(originalBtn);
      originalBtn.remove();
      newBtn.attr(\"id\", \"ebay_registrationbundle_prospecttype_companyWebsite\");
  ")
  fill_registration_form("name", "damanshia@ebay.com", "07736483284329", "Ebay Europe inc", "bad-url")
  click_button("accelerateGrowthRegisterInterestSubmitButton")
end


Then /^I should see an invalid registration 'company web address' error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_prospecttype']/div[5]/ul/li")
end

Before('@db') do
  system 'php src/app/console doctrine:schema:drop --force > /dev/null && php src/app/console doctrine:schema:create > /dev/null'
end
