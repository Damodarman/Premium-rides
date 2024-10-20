<?php


//$params=array(
//'token' => 'y4i37zi31no3y85s',
//'to' => '+385996414577',
//'body' => 'Ovo je testna poruka'
//);
//$curl = curl_init();
//curl_setopt_array($curl, array(
//  CURLOPT_URL => "https://api.ultramsg.com/instance65996/messages/chat",
//  CURLOPT_RETURNTRANSFER => true,
//  CURLOPT_ENCODING => "",
//  CURLOPT_MAXREDIRS => 10,
//  CURLOPT_TIMEOUT => 30,
//  CURLOPT_SSL_VERIFYHOST => 0,
//  CURLOPT_SSL_VERIFYPEER => 0,
//  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//  CURLOPT_CUSTOMREQUEST => "POST",
//  CURLOPT_POSTFIELDS => http_build_query($params),
//  CURLOPT_HTTPHEADER => array(
//    "content-type: application/x-www-form-urlencoded"
//  ),
//));
//
//$response = curl_exec($curl);
//$err = curl_error($curl);
//
//curl_close($curl);
//
//if ($err) {
//  echo "cURL Error #:" . $err;
//} else {
//  echo $response;
//}

?>
<div class="container">
	<div class="row">
		
			<?php if(isset($validation)):?>
	<div class="alert alert-warning">
	   <?= $validation->listErrors() ?>
	</div>
	<?php endif;?>
		<?php if (session()->has('msgPoruka')){ ?>
			<div class="alert <?=session()->getFlashdata('alert-class') ?>">
				<?=session()->getFlashdata('msgPoruka') ?>
			</div>
		<?php } ?>

		<form class="row g-3" action="<?php echo site_url('AdminController/sendmsg');?>" method="post">
		<label for="exampleDataList" class="form-label">Odaberi kome želiš poslati poruku: </label>
		<input name="vozac" class="form-control" list="datalistOptions" id="exampleDataList" placeholder="Type to search...">
		<datalist id="datalistOptions">
			<?php foreach($contacts as $contact): ?>
			<option value="<?php echo $contact['vozac'] ?>" >

			<? endforeach ?>
		</datalist>
			<div class="col-12">
				<label for="poruka" class="form-label">Poruka</label>
				<textarea class="form-control" name ="poruka" placeholder="poruka ...."  rows="3" cols="20"></textarea>

			</div>

			<div class="col-12 text-center">
				<button type="submit" class="btn btn-primary">Pošalji poruku</button>
			  </div>
		</form>
	</div>
</div>