 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>Add New Admin</h3>
                </div>
				    <form method="POST" action="superadmin_dashboard.php">
                <div class="modal-body">
 
				        <input type="text" name="username" placeholder="Username" required style="width:100%; padding: 16px 32px;margin-bottom: 5px; border-radius: 15px;"><br>
				        <input type="email" name="email" placeholder="Email" required style="width:100%; padding: 16px 32px;margin-bottom: 5px; border-radius: 15px;"><br>
				        <input type="password" name="password" placeholder="Password" required style="width:100%; padding: 16px 32px;margin-bottom: 5px; border-radius: 15px;"><br>
				    
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
				    <button type="submit" class="btn btn-primary" name="add_admin">Save Admin</button>
                    <!-- data-dismiss="modal" -->
                </div>
            	</form>
            </div>
        </div>
    </div>