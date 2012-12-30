def archive_reports

  # Archive old reports
  now = Time.now.to_i
  mkdir_p "../../reports/cucumber/html/archives"

  # If old reports exist, shift them out of the way
  if File.exists? "../../reports/cucumber/html/report.html"
    mkdir_p "../../reports/cucumber/html/archives/#{now}"
    mv "../../reports/cucumber/html/report.html", "../..reports/cucumber/html/archives/#{now}"
    mv "../../reports/cucumber/html/screenshots", "../../reports/cucumber/html/archives/#{now}"
  end

  # Clean out old archives
  (Dir["../../reports/cucumber/html/archives/*"].sort.reverse[5..-1] || []).each do |d|
    if File.directory? d
      puts "Removing archive at " << File.basename(d)
      rm_rf d, verbose: false
    end
  end

  mkdir_p "../../reports/cucumber/html/screenshots"

end
