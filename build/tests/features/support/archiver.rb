def archive_reports

# Archive old reports
  now = Time.now.to_i
  mkdir_p "build/reports/cucumber/archives"

# If old reports exist, shift them out of the way
  if File.exists? "build/reports/cucumber/report.html"
    mkdir_p "build/reports/cucumber/archives/#{now}"
    mv "build/reports/cucumber/report.html", "build/reports/cucumber/archives/#{now}"
    mv "build/reports/cucumber/screenshots", "build/reports/cucumber/archives/#{now}/"
  end

  # Clean out old archives
  (Dir["build/reports/cucumber/archives/*"].sort.reverse[5..-1] || []).each do |d|
    if File.directory? d
      puts "Removing archive at " << File.basename(d)
      rm_rf d, verbose: false
    end
  end


  mkdir_p "build/reports/cucumber/screenshots"

end
