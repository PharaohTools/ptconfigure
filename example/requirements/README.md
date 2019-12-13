# Requirements install

``ptconfigure requirements install --role-dir=roles --requirements=requirements/requirements.yml ``


load the requirements file  
do a foreach  
  if directory exists, skip  
  if it doesn't exist, git clone it
  
that's it