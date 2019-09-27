<?php
$url = './details?id='.$model->id;
?>
      <div class="event-box">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-12 col-12">
            <div class="img-holder">
              <img src="../img/residential/default-img.png" alt="Residents image">
            </div>
          </div>
          <div class="col-lg-9 col-md-9 col-sm-12 col-12">
            <div class="row">
              <div class="col-lg-8 col-md-8 col-sm-8 col-8">
                <div class="event-desc">
                  <a href='<?=$url?>'><?=$model->name?></a>
                  <p><?=$model->fkWard->name?></p>
                  <h4 class="m-top">Residents Association</h4>
                  <p><img src="../img/residential/contact.png" alt=""><?=$model->phone1?></p>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-4 ta-center map-link">
                <a href="#" class="direction-map">
                  <img src="../img/residential/map.png" alt="">
                  Get direction
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>