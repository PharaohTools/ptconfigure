def fill_business_detail opts = {}

  default = {
      "companyTradingName" => 'ebay Inc',
      "companyTradingAddress" => 'Richmond London',
      "vatNumber" => "GB1233456678904",
      "companyRegistrationNumber" => "1234556578899",
      "companyTradingWebsite" => "http://www.ebay.com",
      "isCompanyDetailSameAsTradingDetail" => true,
      "companyRegistrationName" => 'Ebay Trading Name Inc',
      "companyRegistrationAddress" => 'Ebay Trading Address, twickenham'
  }

  opts = default.merge opts


  fill_in "ebay_registrationbundle_businessdetailstype_companyTradingName", with: opts["companyTradingName"]
  fill_in "ebay_registrationbundle_businessdetailstype_companyTradingAddress", with: opts["companyTradingAddress"]
  fill_in "ebay_registrationbundle_businessdetailstype_vatNumber", with: opts["vatNumber"]
  fill_in "ebay_registrationbundle_businessdetailstype_companyRegistrationNumber", with: opts["companyRegistrationNumber"]
  fill_in "ebay_registrationbundle_businessdetailstype_companyWebsiteAddress", with: opts["companyTradingWebsite"]

  if opts["isCompanyDetailSameAsTradingDetail"] === true
    check "ebay_registrationbundle_businessdetailstype_isCompanyDetailSameAsTradingDetail"
  else
    uncheck "ebay_registrationbundle_businessdetailstype_isCompanyDetailSameAsTradingDetail"

    fill_in "ebay_registrationbundle_businessdetailstype_companyRegistrationName", with: opts["companyRegistrationName"]
    fill_in "ebay_registrationbundle_businessdetailstype_companyRegistrationAddress", with: opts["companyRegistrationAddress"]
  end

  if opts["shouldSendCompanyRegistrationDetails"] === true
    fill_in "ebay_registrationbundle_businessdetailstype_companyRegistrationName", with: opts["companyRegistrationName"]
    fill_in "ebay_registrationbundle_businessdetailstype_companyRegistrationAddress", with: opts["companyRegistrationAddress"]
  end

end

def reachingBusinessDetailsPage
  visit "/register"
  fill_contact_detail
  click_button("accelerateGrowthContactDetailFormSubmitButton");
end

Given /^I visit the registration page on the business details section$/ do
  reachingBusinessDetailsPage
end


Then /^I should see the introduction text for the Business Details$/ do
  find('h1')
  find(:accelerateGrowthBusinessDetailsExplanationText)
end


When /^I submit a valid Business Details form$/ do
  minimumSet = {"isCompanyDetailSameAsTradingDetail" => true, "vatNumber" => "", "companyRegistrationNumber" => "" }
  fill_business_detail minimumSet
  click_button("accelerateGrowthBusinessFormSubmitButton")
end

Then /^I should be redirected to 'your online business' Form$/ do
  page.current_path.should =~ /register\/(.+)\/your-online-business/
end

When /^I submit a an invalid business detail form with an empty trading name name$/ do
  page.execute_script("$('#ebay_registrationbundle_businessdetailstype_companyTradingName').removeAttr('required')")
  invalidSet = {"isCompanyDetailSameAsTradingDetail" => false, 'companyTradingName' => ''}
  fill_business_detail invalidSet
  click_button("accelerateGrowthBusinessFormSubmitButton");
end

Then /^I should see an 'empty name' error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_businessdetailstype']/div[1]/ul/li")
end

When /^I submit a an invalid business detail form with an empty company trading address field$/ do
  page.execute_script("$('#ebay_registrationbundle_businessdetailstype_companyTradingAddress').removeAttr('required')")
  invalidSet = {'companyTradingAddress' => ''}
  fill_business_detail invalidSet
  click_button("accelerateGrowthBusinessFormSubmitButton");
end

Then /^I should see an 'empty company trading address' error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_businessdetailstype']/div[2]/ul/li")
end


When /^I submit a an invalid business detail form with an empty company website address field$/ do
  page.execute_script("$('#ebay_registrationbundle_businessdetailstype_companyWebsiteAddress').removeAttr('required')")
  invalidSet = {"isCompanyDetailSameAsTradingDetail" => false, 'companyTradingWebsite' => ''}
  fill_business_detail invalidSet
  click_button("accelerateGrowthBusinessFormSubmitButton");
end


Then /^I should see an 'empty company website address' error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_businessdetailstype']/div[5]/ul/li")
end

When /^I submit a an invalid business detail form with an invalid company website address field$/ do
  page.execute_script("
      var originalBtn = $(\"#ebay_registrationbundle_businessdetailstype_companyWebsiteAddress\");
      var newBtn = originalBtn.clone();
      newBtn.attr(\"type\", \"text\");
      newBtn.insertBefore(originalBtn);
      originalBtn.remove();
      newBtn.attr(\"id\", \"ebay_registrationbundle_businessdetailstype_companyWebsiteAddress\");
  ")
  invalidSet = {"isCompanyDetailSameAsTradingDetail" => false, 'companyTradingWebsite' => 'davesaysthisisnotaurl'}
  fill_business_detail invalidSet
  click_button("accelerateGrowthBusinessFormSubmitButton");
end


Then /^I should see an 'invalid company website address' error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_businessdetailstype']/div[5]/ul/li")
end

When /^I submit a valid business detail form including a Registered Company Name\/Address$/ do
  minimumSet = {"isCompanyDetailSameAsTradingDetail" => false}
  fill_business_detail minimumSet
  click_button("accelerateGrowthBusinessFormSubmitButton");
end

When /^I select the 'same details' checkbox$/ do
  minimumSet = {"isCompanyDetailSameAsTradingDetail" => true}
  fill_business_detail minimumSet
end

Then /^I should see company registration name and address disabled$/ do
  find("#ebay_registrationbundle_businessdetailstype_companyRegistrationName[disabled]")
  find("#ebay_registrationbundle_businessdetailstype_companyRegistrationAddress[disabled]")
end

And /^I deselect the 'same details' checkbox$/ do
  minimumSet = {"isCompanyDetailSameAsTradingDetail" => false}
  fill_business_detail minimumSet
end

Then /^the Registration company name\/address fields fields should be re\-enabled$/ do
  find("#ebay_registrationbundle_businessdetailstype_companyRegistrationName")
  find("#ebay_registrationbundle_businessdetailstype_companyRegistrationAddress")
end

When /^I submit a an invalid business detail form with 'same details' ticked and company registration name$/ do
  invalidSet = {
      "isCompanyDetailSameAsTradingDetail" => true,
      "companyRegistrationName" => 'Ebay Trading Name Inc',
      "companyRegistrationAddress" => 'Ebay Trading Address, twickenham',
      "shouldSendCompanyRegistrationDetails" => true
  }
  page.execute_script("$('#ebay_registrationbundle_businessdetailstype_isCompanyDetailSameAsTradingDetail').unbind('change')") #remove the JS chenge event
  fill_business_detail invalidSet
  click_button("accelerateGrowthBusinessFormSubmitButton");
end

Then /^I should see an invalid company registration details error message$/ do
  page.should have_xpath(".//*[@id='ebay_registrationbundle_businessdetailstype']/div[7]/ul/li")
end

Given /^I visit the registration page on the business details section with an invalid business ID$/ do
  randomHash = (0...50).map{ ('a'..'z').to_a[rand(26)] }.join
  visit "/register"
  fill_contact_detail
  click_button("accelerateGrowthContactDetailFormSubmitButton")
  visit "/register/" + randomHash + "/business-details"
end

And /^I click on the 'back to business details' button$/ do
  click_link("accelerateGrowthContactDetailFormPreviousButton")
end

Then /^I should the 'business details' form pre\-populated with previous data$/ do
  find("#ebay_registrationbundle_businessdetailstype_companyTradingName").value.should == 'ebay Inc'
  find("#ebay_registrationbundle_businessdetailstype_companyTradingAddress").value.should == 'Richmond London'
  find("#ebay_registrationbundle_businessdetailstype_companyWebsiteAddress").value.should == "http://www.ebay.com"
  find("#ebay_registrationbundle_businessdetailstype_isCompanyDetailSameAsTradingDetail").should be_checked
end

When /^I submit a an invalid business detail form with 'same details' un\-ticked and an empty company registration name field$/ do
  invalidSet = {
      "isCompanyDetailSameAsTradingDetail" => false,
      "companyRegistrationName" => '',
      "shouldSendCompanyRegistrationDetails" => true
  }
  fill_business_detail invalidSet

  page.execute_script("$('#ebay_registrationbundle_businessdetailstype_companyRegistrationName').removeAttr('required')")

  click_button("accelerateGrowthBusinessFormSubmitButton");
end

When /^I submit a an invalid business detail form with 'same details' un\-ticked and an empty company registration address field$/ do
  invalidSet = {
      "isCompanyDetailSameAsTradingDetail" => false,
      "companyRegistrationAddress" => '',
      "shouldSendCompanyRegistrationDetails" => true
  }
  fill_business_detail invalidSet

  page.execute_script("$('#ebay_registrationbundle_businessdetailstype_companyRegistrationAddress').removeAttr('required')")

  click_button("accelerateGrowthBusinessFormSubmitButton");
end