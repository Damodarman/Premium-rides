<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Password recovery</title>
  </head>
  <body>
    <div class="container">
                 <?php if(session()->getFlashdata('msgPoruka')):?>
                    <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                       <?= session()->getFlashdata('msgPoruka') ?>
                    </div>
                <?php endif;?>
       <div class="row justify-content-md-center">
            <div class="col col-xxl-5 col-xl-5 col-lg-5 col-md-8 col-sm-12">
                
                <h2>Get password reset email</h2>
                
                <?php if(session()->getFlashdata('msg')):?>
                    <div class="alert <?=session()->getFlashdata('alert-class') ?>">
                       <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif;?>
                <form action="<?php echo base_url(); ?>/index.php/SignupController/passwordReset" method="post">
                    <div class="form-group mb-3">
                        <input type="email" name="email" placeholder="Email" class="form-control" >
                    </div>                    
                    <div class="d-grid">
                         <button type="submit" class="btn btn-success">Reset password</button>
                    </div>     
                </form>
            </div>
              
        </div>
    </div>
  </body>
</html>