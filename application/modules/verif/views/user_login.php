<div class="modal-dialog modal-default modal-sm">
  <div class="modal-content no_radius">
  	<div class="modal-header">
      <h4 class="modal-title text-center">Login :: Verif RSI Pati <small>ver 2.0</small></h4>
    </div>
    <div class="modal-body">
	  	<form role="form" method="POST" action="<?php echo $action;?>">
		    <div class="modal-body">
		    	<?php echo @alert($msg, 'danger');?>
		    	<div class="form-group">
				    <label for="username"><span class="fa fa-user"></span> Username</label>
				    <input name="uname" class="form-control no_radius" id="username" placeholder="Masukkan username" type="text">
				  </div>
				  <div class="form-group">
				    <label for="password"><span class="fa fa-eye-slash"></span> Password</label>
				    <input name="upass" class="form-control no_radius" id="password" placeholder="Masukkan password" type="password">
				  </div>
		    </div>
		    <div class="modal-footer">
		      <button type="submit" class="btn btn-success no_radius"><span class="fa fa-unlock-alt"></span> Masuk</button>
		    </div>
			</form>
    </div>
	</div>
</div>