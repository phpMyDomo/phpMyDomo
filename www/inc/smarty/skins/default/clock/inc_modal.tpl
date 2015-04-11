	<div class="modal" id="modal_alarm" tabindex="-1" role="dialog" aria-labelledby="modal_alarm_label" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<ul class="nav nav-pills nav-justified" role="tablist" id="jsModalTabs">
					<li role="presentation" class="active"><a role="tab" data-toggle="pill" href="#tab_timer"><i class='fa fa-clock-o'></i> {$l.modal.tab_timer}</a></li>
					<li role="presentation"><a role="tab" data-toggle="pill" href="#tab_alarm"><i class='fa fa-bell'></i> {$l.modal.tab_alarm}</a></li>
				</ul>
				<div class="modal-body">

					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="tab_timer">
						
							<div class="form-horizontal">
								<div class="form-group form-group-lg">
									<label class="col-xs-3 control-label">{$l.modal.duration}</label>
									<div class="col-xs-4">
										<div class="input-group">
											<select id="jsInputTimerMin" class="form-control">{html_options options=$data.opt_min2 selected=0}</select>
											<div class="input-group-addon">{$l.modal.minutes}</div>
										</div>
									</div>
									<div class="col-xs-4">								
										<div class="input-group">
											<select id="jsInputTimerSec" class="form-control">{html_options options=$data.opt_sec selected=0}</select>
											<div class="input-group-addon">{$l.modal.seconds}</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div role="tabpanel" class="tab-pane" id="tab_alarm">
							<div class="form-horizontal">
								<div class="form-group form-group-lg">
									<label class="col-xs-3 control-label">{$l.modal.end_time}</label>
									<div class="col-xs-4">
										<div class="input-group">
											<select id="jsInputAlarmHour" class="form-control">{html_options options=$data.opt_hour selected={intval($smarty.now|date_format:"%H")}}</select>
											<div class="input-group-addon">{$l.modal.hours}</div>
										</div>
									</div>
									<div class="col-xs-4">								
										<div class="input-group">
											<select id="jsInputAlarmMin" class="form-control">{html_options options=$data.opt_min selected={intval($smarty.now|date_format:"%M")}}</select>
											<div class="input-group-addon">{$l.modal.minutes}</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
							<div class="form-horizontal">
								<div class="form-group form-group-lg">
									<label class="col-xs-3 control-label" for="jsInputSound">{$l.modal.sound}</label>
  									<div class="col-xs-2 checkbox">
										<input type="checkbox" id="jsInputSound" value=1 checked="checked">
									</div>
  									<div class="col-xs-5">
										<select id="jsSelectSound" class="form-control">{html_options options=$data.opt_sounds selected=''}</select>
									</div>
  									<div class="col-xs-1">
										<button type="button" class="btn btn-success" id="jsButSoundPlay"><i class="fa fa-play"></i></button>
										<button type="button" class="btn btn-default" id="jsButSoundStop"><i class="fa fa-stop"></i></button>
									</div>
								</div>
								<div class="form-group  form-group-lg">
									<label class="col-xs-3 control-label" for="jsInputLight">{$l.modal.light}</label>
  									<div class="col-xs-8 checkbox-inline">
										<input type="checkbox" id="jsInputLight" value=1 checked="checked">
									</div>
								</div>
							</div>

							<div class='modal_buttons text-center'>
								<button type="button" class="btn btn-lg btn-default" data-dismiss="modal">{$l.modal.but_cancel}</button> 
								<button type="button" class="btn btn-lg btn-primary" data-dismiss="modal" id="jsButAlarm"><i class='fa fa-bell'></i> {$l.modal.but_alarm}</button> 
								<button type="button" class="btn btn-lg btn-primary" data-dismiss="modal" id="jsButTimer"><i class='fa fa-clock-o'></i> {$l.modal.but_timer}</button>
							</div>

				</div>
			</div>
		</div>
	</div>
