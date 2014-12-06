$(document).submit(function() {
                  if($('[name="username"]').empty())
                  {
                      alert("Username cannot be empty.");
                      return false;
                  }
                
    
                  if($('[name="password"]').empty())
                  {
                     alert("Password cannot be empty.");
                      return false;
                  }
                  
                    return true;
                  });