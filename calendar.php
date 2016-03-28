<?php
session_start();
include_once("database.php");
ini_set("session.cookie_httponly", 1);

?>

<!DOCTYPE html>
    <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
            <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <script src="http://classes.engineering.wustl.edu/cse330/content/calendar.js"></script>

            <title>Calendar</title>
            <style>
                ul {
                    list-style-type: none;
                    margin: 0;
                    padding: 0;
                    overflow: hidden;
                    background-color: #333;
                }
            
                li {
                    float: left;
                }
                
                li a {
                    display: block;
                    color: white;
                    text-align: center;
                    padding: 14px 16px;
                    text-decoration: none;
                }
                
                li a:hover {
                    background-color: #111;
                }
                
                /* Style The Dropdown Button */
                .dropbtn {
                    background-color: #4CAF50;
                    color: white;
                    padding: 16px;
                    font-size: 16px;
                    border: none;
                    cursor: pointer;
                }
                
                /* The container <div> - needed to position the dropdown content */
                .dropdown {
                    position: relative;
                    display: inline-block;
                }
                
                /* Dropdown Content (Hidden by Default) */
                .dropdown-content {
                    display: none;
                    position: absolute;
                    background-color: #f9f9f9;
                    min-width: 160px;
                    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
                }
                
                /* Links inside the dropdown */
                .dropdown-content a {
                    color: black;
                    padding: 12px 16px;
                    text-decoration: none;
                    display: block;
                }
                
                /* Change color of dropdown links on hover */
                .dropdown-content a:hover {background-color: #f1f1f1}
                
                /* Show the dropdown menu on hover */
                .dropdown:hover .dropdown-content {
                    display: block;
                }
                
                /* Change the background color of the dropdown button when the dropdown content is shown */
                .dropdown:hover .dropbtn {
                    background-color: #3e8e41;
                }
                
            </style>

        </head>
        
        
        <body>
            <div class="dropdown">
                <?php
                    if(isset($_SESSION['user_id'])) {
                ?>
                <button id="greet" class="dropbtn">Welcome, <?php echo $_SESSION['username']; ?>!</button>
                <?php
                    } else {
                ?>
                <button id="greet" class="dropbtn">Welcome, Guest!</button>
                <?php
                    }
                ?>
                <div class="dropdown-content">
                    <a href="#login" data-toggle="modal">Login</a>
                    <a href="#register" data-toggle="modal">Register</a>
                    <a href="#logout" id="logout">Log Out</a>
                </div>
            </div>
            <div>
                <ul>
                    <li><a href="#" id="today">Today</a></li>
                    <li id="previous-month"><a href="#">❮</a></li>
                    <li>
                        <a href="#" id="current-month">Current Month</a>
                    </li>
                    <li id="next-month"><a href="#">❯</a></li>
                    <li><a href="#add-event" data-toggle="modal">Add Event</a></li>
                    
                </ul>
            </div>
            
            
            <div class="container">            
              <!-- Login -->
              <div id="login" class="modal fade" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Login</h4>
                    </div>
                    <div class="modal-body">
                      <div>
                        <input id="login-username" type="text" placeholder="Username" name="username" required>
                        <input id="login-password" type="password" placeholder="Password" name="password" required>
                        <button id="login-submit" class="btn btn-default">Login</button>

                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                  
                </div>
              </div>
              
              <!-- Register -->
              <div id="register" class="modal fade" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Register</h4>
                    </div>
                    <div class="modal-body">
                      <div>
                        <input id="register-username" type="text" placeholder="Username" name="username" required>
                        <input id="register-password" type="password" placeholder="Password" name="password" required>
                        <button id="register-submit" class="btn btn-default">Register</button>

                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                  
                </div>
              </div>
              
              
              <!-- Add Event -->
              <div id="add-event" class="modal fade" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Add Event</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Category:</label>
                            <div>
                                <input type="radio" name="event-type" value="work" id="work" />
                                <label>Work</label>
                                <input type="radio" name="event-type" value="school" id="school" />
                                <label>School</label>
                                <input type="radio" name="event-type" value="entertainment" id="entertainment" />
                                <label>Entertainment</label>

                            </div>
                        </div>
                        
                        
                        <div>
                            <label for="group-user">Group with (separate users with ";"): </label>
                            <input id="group-user" type="text" placeholder="username" name="group-user" required>
                        </div>
                        
                      <div>
                        <label for="event-title">Title:</label>
                        <input id="event-title" type="text" placeholder="Title" name="event-title" required>
                        <br>
                        <label for="event-detail">Content:</label>
                        <textarea id="event-detail" rows="3" cols="20" class="form-control input-lg" name="event-detail">
                        </textarea>
                        <label for="event-date">Date<small>(yyyy-mm-dd)</small>:</label>
                        <input id="event-date" type="text" class="form-control input-lg" name="event-date" placeholder="yyyy-mm-dd" required>

                        <label for="event-time">Time<small>(HH:MM)</small>:</label>
                        <input id="event-time" type="text" class="form-control input-lg" name="event-time" placeholder="HH:MM" required>
                        
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" id="token" />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button id="add-event-submit" class="btn btn-default" data-dismiss="modal">Add Event</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
            
           <!--DISPLAY EVENT-->
           <div id="display" class="modal fade" role="dialog">
               <div class="modal-dialog">
                   <!-- Modal content-->
                   <div class="modal-content">
                       <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal">&times;</button>
                           <h4 class="modal-title">Event</h4>                       
                       </div>
                       <div class="modal-body" id="event-body">
                           <div id="view-event"></div>
                       </div>
                       <div class="modal-footer">
                          <button id="update-event" class="btn btn-info btn-lg btn-block btn-in">Update</button>
                           <button id="delete-event" class="btn btn-info btn-lg btn-block btn-in">Delete</button>
                           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                       </div>
                   </div>
        
               </div>
           </div>
           
                      
           
           

            
              <!-- Update Event -->
              <div id="update" class="modal fade" role="dialog">
                <div class="modal-dialog">
                
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Update Event</h4>
                    </div>
                    <div class="modal-body">
                        
                        <div class="form-group">
                            <label>Category:</label>
                            <div>
                                <input type="radio" name="event-type-update" value="work" id="work2" />
                                <label>Work</label>
                                <input type="radio" name="event-type-update" value="school" id="school2" />
                                <label>School</label>
                                <input type="radio" name="event-type-update" value="entertainment" id="entertainment2" />
                                <label>Entertainment</label>

                            </div>
                        </div>
                        
                        
                        <div>
                            <label for="group-user-update">Group with (separate users with ";"): </label>
                            <input id="group-user-update" type="text" placeholder="username" name="group-user-update" required>
                        </div>

                        
                        
                      <div>
                        <label for="event-title-update">Title:</label>
                        <input id="event-title-update" type="text" placeholder="Title" name="event-title" required>
                        <br>
                        <label for="event-detail-update">Content:</label>
                        <textarea id="event-detail-update" rows="3" cols="20" class="form-control input-lg" name="event-detail">
                        </textarea>
                        <label for="event-date-update">Date<small>(yyyy-mm-dd)</small>:</label>
                        <input id="event-date-update" type="text" class="form-control input-lg" name="event-date" placeholder="yyyy-mm-dd" required>

                        <label for="event-time-update">Time<small>(HH:MM)</small>:</label>
                        <input id="event-time-update" type="text" class="form-control input-lg" name="event-time" placeholder="HH:MM" required>
                        
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" id="token-update" />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button id="update-event-submit" class="btn btn-default" data-dismiss="modal">Update Event</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                  </div>
                  
                </div>
              </div>
            
            
            
            
            
            
            <!-- Calendar-->
                <div id="calendar-month" class="tab-pane fade in active">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>haha</th>
                                <th>Sunday</th>
                                <th>Monday</th>
                                <th>Tuesday</th>
                                <th>Wednesday</th>
                                <th>Thursday</th>
                                <th>Friday</th>
                                <th>Saturday</th>
                            </tr>
                        </thead>
                        <tbody id="calendar-body">
                        </tbody>
                    </table>
                </div>
            
            
            
            
            <script type="text/javascript" src="calendar.js"></script>

            
            
            
        


        </body>
    </html>