<?php
$url = './details?id='.$model->id;
?>
          <div class="col-lg-9 col-md-9 col-sm-12 col-12">
            <div class="row">
              <div class="col-lg-8 col-md-8 col-sm-8 col-8">
               
                  <a href='<?=$url?>'><?=$model->name?></a>
                  <p><?=$model->fkWard->name?></p>
                  <h4 class="m-top">Residents Association</h4>
                  <p><img src="../img/residential/contact.png" alt=""><?=$model->phone1?></p>
                </div>
              
            
            </div>
          </div>
        