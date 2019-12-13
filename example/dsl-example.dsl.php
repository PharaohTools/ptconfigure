RunCommand install
  label "Stop this Autopilot if the run type parameter is not set"
  guess
  command "exit 1"
  not_when "{{{ Param::run_type }}}"

Logging log
  message "This is the first Pharaoh Yaml DSL"
  guess
