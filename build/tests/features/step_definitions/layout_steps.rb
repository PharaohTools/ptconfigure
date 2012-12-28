# Layout steps, should be minimal, we don't test much layout (on purpose)

Then /^the ebay header should be visible$/ do
  page.should have_css "#gh"
  find("#gh").should be_visible
  find("#gh").text.size.should > 0
end

Then /^the ebay footer should be visible$/ do
  page.should have_css "#glbfooter"
  find("#glbfooter").should be_visible
  #find("#glbfooter").text.size.should > 0 #minimal footer does not have text no more
end