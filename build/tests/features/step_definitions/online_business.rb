$defaultFormData = {
    "otherEcommercePlatform" => false,
    "otherListingMethods" => false,
    "ebayUserId" => "ebayUserId",
    "ebayStoreName" => "My super Store",
    "haveAccountBelowStandard" => true,
    "doesSellOnAmazon" => true,
    "inventoryTool" => false,
    "totalAnnualRevenue" => "222",
    "totalOnlineAnnualRevenue" => "12542",
    "totalEbayAnnualRevenue" => "14582",
    "totalNumberOfSku" => "10",
    "totalOnlineNumberOfSku" => "30",
    "totalEbayNumberOfSku" => "15",
    "expectedNumberOfSku" => "45",
    "ecommercePlatform" => "Cubecart",
    "listingTool" => "eSellerPro",
    "inventoryManagementTool" => "I use my listing tool"
}

def fill_online_business_detail opts = {}

  opts = $defaultFormData.merge opts

  if opts["otherEcommercePlatform"]
    fill_in "ebay_registrationbundle_onlinebusinesstype_otherEcommercePlatform", with: opts["otherEcommercePlatform"]
  end

  if opts["otherListingMethods"]
    fill_in "ebay_registrationbundle_onlinebusinesstype_otherListingMethods", with: opts["otherListingMethods"]
  end

  fill_in "ebay_registrationbundle_onlinebusinesstype_ebayUserId", with: opts["ebayUserId"]
  fill_in "ebay_registrationbundle_onlinebusinesstype_ebayStoreName", with: opts["ebayStoreName"]

  if opts["haveAccountBelowStandard"]
    page.select 'Yes', :from => 'ebay_registrationbundle_onlinebusinesstype_haveAccountBelowStandard'
  else
    page.select 'No', :from => 'ebay_registrationbundle_onlinebusinesstype_haveAccountBelowStandard'
  end

  if opts["doesSellOnAmazon"]
    page.select 'Yes', :from => 'ebay_registrationbundle_onlinebusinesstype_doesSellOnAmazon'
  else
    page.select 'No', :from => 'ebay_registrationbundle_onlinebusinesstype_doesSellOnAmazon'
  end

  if opts["inventoryTool"]
    fill_in "ebay_registrationbundle_onlinebusinesstype_inventoryTool", with: opts["inventoryTool"]
  end

  fill_in "ebay_registrationbundle_onlinebusinesstype_totalAnnualRevenue", with: opts["totalAnnualRevenue"]
  fill_in "ebay_registrationbundle_onlinebusinesstype_totalOnlineAnnualRevenue", with: opts["totalOnlineAnnualRevenue"]
  fill_in "ebay_registrationbundle_onlinebusinesstype_totalEbayAnnualRevenue", with: opts["totalEbayAnnualRevenue"]
  fill_in "ebay_registrationbundle_onlinebusinesstype_totalNumberOfSku", with: opts["totalNumberOfSku"]
  fill_in "ebay_registrationbundle_onlinebusinesstype_totalOnlineNumberOfSku", with: opts["totalOnlineNumberOfSku"]
  fill_in "ebay_registrationbundle_onlinebusinesstype_totalEbayNumberOfSku", with: opts["totalEbayNumberOfSku"]
  fill_in "ebay_registrationbundle_onlinebusinesstype_expectedNumberOfSku", with: opts["expectedNumberOfSku"]

  select(opts["ecommercePlatform"], :from => 'ebay_registrationbundle_onlinebusinesstype_ecommercePlatform')
  select(opts["listingTool"], :from => 'ebay_registrationbundle_onlinebusinesstype_listingTool')
  select(opts["inventoryManagementTool"], :from => 'ebay_registrationbundle_onlinebusinesstype_inventoryManagementTool')
end

def reachingOnlineBusinessPage
  visit "/register"
  fill_contact_detail
  click_button("accelerateGrowthContactDetailFormSubmitButton");

  fill_business_detail
  click_button("accelerateGrowthBusinessFormSubmitButton")
end

Given /^I visit the registration page on the 'Online business' section$/ do
  reachingOnlineBusinessPage
end

Then /^I should see the introduction text for the 'Online Business' section$/ do
  find('#accelerateGrowthYourOnlineBusinessExplanationText')
end

When /^I submit a valid Online Business Details form$/ do
  fill_online_business_detail
  click_button("accelerateGrowthOnlineBusinessFormSubmitButton")
end

Then /^I should be redirected to 'your eBayStore' Form$/ do
  page.current_path.should =~ /register\/(.+)\/your-ebay-store/
end

Then /^I should see the 'other platform' field disabled$/ do
  find("#ebay_registrationbundle_onlinebusinesstype_otherEcommercePlatform[disabled]")
end

Then /^I should see the 'other listing tool' field disabled$/ do
  find("#ebay_registrationbundle_onlinebusinesstype_otherListingMethods[disabled]")
end

Then /^I should see the 'dedicated software tool' field disabled$/ do
  find("#ebay_registrationbundle_onlinebusinesstype_inventoryTool[disabled]")
end

When /^I select 'other' e\-commerce platform option$/ do
  select('Other', :from => 'ebay_registrationbundle_onlinebusinesstype_ecommercePlatform')
end

Then /^I should see the 'other platform' field re\-enabled$/ do
  find("#ebay_registrationbundle_onlinebusinesstype_otherEcommercePlatform:not([DISABLED])")
end

When /^I select other listing tool option$/ do
  select('Other', :from => 'ebay_registrationbundle_onlinebusinesstype_listingTool')
end

Then /^I should see the 'other listing tool' field re\-enabled$/ do
  find("#ebay_registrationbundle_onlinebusinesstype_otherListingMethods:not([DISABLED])")
end

When /^I select 'I use a dedicated software option' option$/ do
  select('I use a dedicated software / website', :from => 'ebay_registrationbundle_onlinebusinesstype_inventoryManagementTool')
end

Then /^I should see the 'Dedicated software tool' field re\-enabled$/ do
  find("#ebay_registrationbundle_onlinebusinesstype_inventoryManagementTool:not([DISABLED])")
end

When /^I click the previous button on the 'your eBay store' page$/ do
  click_link("accelerateGrowthYourEbayStoreFormPreviousButton")
end

And /^I should see the persisted data from the previous request$/ do
  find("#ebay_registrationbundle_onlinebusinesstype_otherEcommercePlatform").value.should == ""
  find("#ebay_registrationbundle_onlinebusinesstype_otherListingMethods").value.should == ""
  find("#ebay_registrationbundle_onlinebusinesstype_haveAccountBelowStandard").value.should == "1"
  find("#ebay_registrationbundle_onlinebusinesstype_doesSellOnAmazon").value.should == "1"
  find("#ebay_registrationbundle_onlinebusinesstype_inventoryTool").value.should == ""
  find("#ebay_registrationbundle_onlinebusinesstype_totalAnnualRevenue").value.should == $defaultFormData["totalAnnualRevenue"]
  find("#ebay_registrationbundle_onlinebusinesstype_totalOnlineAnnualRevenue").value.should == $defaultFormData["totalOnlineAnnualRevenue"]
  find("#ebay_registrationbundle_onlinebusinesstype_totalEbayAnnualRevenue").value.should == $defaultFormData["totalEbayAnnualRevenue"]
  find("#ebay_registrationbundle_onlinebusinesstype_totalNumberOfSku").value.should == $defaultFormData["totalNumberOfSku"]
  find("#ebay_registrationbundle_onlinebusinesstype_totalOnlineNumberOfSku").value.should == $defaultFormData["totalOnlineNumberOfSku"]
  find("#ebay_registrationbundle_onlinebusinesstype_totalEbayNumberOfSku").value.should == $defaultFormData["totalEbayNumberOfSku"]
  find("#ebay_registrationbundle_onlinebusinesstype_expectedNumberOfSku").value.should == $defaultFormData["expectedNumberOfSku"]
  find("#ebay_registrationbundle_onlinebusinesstype_ecommercePlatform").value.should == "5"
  find("#ebay_registrationbundle_onlinebusinesstype_listingTool").value.should == "4"
  find("#ebay_registrationbundle_onlinebusinesstype_inventoryManagementTool").value.should == "3"
  find("#ebay_registrationbundle_onlinebusinesstype_ebayUserId").value.should == $defaultFormData["ebayUserId"]
  find("#ebay_registrationbundle_onlinebusinesstype_ebayStoreName").value.should == $defaultFormData["ebayStoreName"]
  find("#ebay_registrationbundle_onlinebusinesstype_ebayUserId").value.should == $defaultFormData["ebayUserId"]
end

When /^I submit the 'Online Business' form supplying information for both platform and other platform field$/ do
  fill_online_business_detail
  page.execute_script('enableOtherEcommercePlatformField()');
  fill_in "ebay_registrationbundle_onlinebusinesstype_otherEcommercePlatform", with: "BigCommerce"
  click_button('accelerateGrowthOnlineBusinessFormSubmitButton')
end

When /^I submit the 'Online Business' form supplying no information for neither platform and other platform field$/ do

  page.execute_script(' jQuery(\'<input type="text" value="" name="ebay_registrationbundle_onlinebusinesstype[ecommercePlatform]" id="ebay_registrationbundle_onlinebusinesstype_ecommercePlatform"/>\').insertAfter(\'.inner\') ' );
  fill_in "ebay_registrationbundle_onlinebusinesstype_otherEcommercePlatform", with: "BigCommerce"

  opts = { "ecommercePlatform"=>"" , "otherEcommercePlatform"=>"" }
  page.execute_script('enableOtherEcommercePlatformField()')
  fill_online_business_detail opts
  sleep 20
  click_button('accelerateGrowthOnlineBusinessFormSubmitButton')
end