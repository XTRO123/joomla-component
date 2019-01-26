<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 3 or later; see LICENSE.txt
*/

/** @var $this AtomsViewTour */
defined( '_JEXEC' ) or die; // No direct access

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

?>

<div class="item-page <?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
    <div class="tour-page">
        <div class="tour-cover" style="background-image: url('<?php echo $this->item->tour->gallery[0]->original; ?>');">
            <div class="bottom-right-buttons">
                <span id="atoms-basic-modal" data-open-id="album-tour" class="btn btn-default" >Смотреть фотографии</span>
                <a href="<?php echo AtomsSiteHelper::createLink($this->slug, array('payment'), array('parent', $this->key), (!empty($this->item->tour->nearest_trip->links->self)?'&path='.$this->item->tour->nearest_trip->links->self:'')); ?>" class="btn btn-primary btn-lg">
                    <b>Купить онлайн</b>
                </a>
            </div>
        </div>
        <div class="container-fluid max-width relative no-paddings">
            <!-- tour main info -->
            <div class="col-sm-7 tour-content large-paddings">
                <div class="tour-detailed-view">
                    <div class="tour-item">
                        <div class="main-block wide">
                            <div class="top-line">
                                <b class="text-primary"><?php echo $this->item->tour->age_limit; ?>+</b>
                                <span><?php echo $this->item->tour->duration_type . ' ' . $this->item->tour->type ?> тур</span>
                            </div>
                            <h1 class="tour-name"><?php echo $this->item->tour->name ?></h1>
                            <!-- labels: types tours / booking kind / hotels -->
                            <div class="tour-labels">
                                <!-- booking kind -->
                                <?php if( isset($this->item->tour->nearest_trip->booking_kind) && $this->item->tour->nearest_trip->booking_kind == "instant_booking" ): ?>
                                <span class="label label-scarlet" data-toggle="tooltip" data-placement="bottom" title="Места уже подтверждены! Этот тур можно приобрести без дополнительного согласования." data-original-title="Места уже подтверждены! Этот тур можно приобрести без дополнительного согласования.">Мгновенная покупка</span>
                                <?php endif; ?>
                                <!-- next tours -->
                                <?php if($this->item->tour->is_group_tour): ?>
                                    <span class="label label-yellow" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Тур для организованных групп: школ, корпоративных клиентов и других.">Для групп</span>
                                <?php endif ?>
                                <?php if($this->item->tour->is_reception_tour): ?>
                                    <span class="label label-gray" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Тур типа Приём. Вы добираетесь до места начала тура самостоятельно.">Приём</span>
                                <?php endif; ?>
                                <?php if($this->item->tour->is_departure_tour): ?>
                                    <span class="label label-gray" data-toggle="tooltip" data-placement="bottom" title="Тур типа Выезд. Вы вместе с экскурсионной группой выезжаете на автобусе из своего города и двигаетесь к цели путешествия." data-original-title="Тур типа Выезд. Вы вместе с экскурсионной группой выезжаете на автобусе из своего города и двигаетесь к цели путешествия.">Выезд</span>
                                <?php endif; ?>
                                <?php if($this->item->tour->is_cyclic_tour): ?>
                                    <span class="label label-gray" data-toggle="tooltip" data-placement="bottom" title="В данном туре можно самостоятельно выбрать продолжительность и дату заезда." data-original-title="В данном туре можно самостоятельно выбрать продолжительность и дату заезда.">Тур-конструктор</span>
                                <?php endif; ?>
                                <!-- hotels -->
                                <?php if( isset($this->item->tour->nearest_trip->hotels) && ($hotels = count($this->item->tour->nearest_trip->hotels)) > 1 ): ?>
                                    <?php //$hotels = $this->item->tour->hotels_number; ?>
                                    <span class="label label-gray" data-toggle="tooltip" data-placement="bottom" title="Для этого тура предусмотрено несколько гостиниц. От выбора гостиницы будет зависеть стоимость тура." data-original-title="Для этого тура предусмотрено несколько гостиниц. От выбора гостиницы будет зависеть стоимость тура."><?php echo $hotels; ?> гостиницы</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!-- duration -->
                    <h3>Продолжительность</h3>
                    <div class="tour-info tour-duration">
                        <!-- hours -->
                        <?php if( isset($this->item->tour->duration_hours) ): ?>
                            <span class="caption">Длительность:</span>
                            <b><?php echo JHtml::_('duration.duration', $this->item->tour->duration_hours, 'h'); ?></b>
                        <!-- days -->
                        <?php else: ?> 
                            <!-- duration -->
                            <b><?php echo (@$this->item->tour->is_cyclic_tour) ? JHtml::_('duration.duration', $this->item->tour->duration_days, 'd', false, '—', '') : JHtml::_('duration.duration', $this->item->tour->duration_days, 'd', true, ' / '); ?><?php echo JHtml::_('duration.duration', $this->item->tour->duration_nights, 'n'); ?></b>
                        <?php endif; ?>
                    </div>
                    <h3>Маршрут тура</h3>
                    <p>
                    Места показа:&nbsp;
                    <span>
                        <?php 
                        foreach( $this->item->tour->route_cities_list as $city ) { 
                            if( $city == end($this->item->tour->route_cities_list) )
                                echo $city;
                            else
                                echo $city . ', ';
                        }
                        ?>
                    </span>
                    <br />
                    Город начала<?php if(isset($this->item->tour->end_city)): ?> / завершения<?php endif; ?>:&nbsp;<?php echo $this->item->tour->start_city . (isset($this->item->tour->end_city) ? ' / ' . $this->item->tour->end_city : ''); ?>
                    </p>
                
                    <?php if( isset($this->item->tour->description) ): ?>
                        <h3>Описание тура</h3>
                        <div><p><?php echo nl2br($this->item->tour->description); ?></p></div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- tour dynamic part (right column) -->
            <div class="col-sm-5 no-paddings middle-to-right">
                <div class="col-sm-11 col-sm-offset-1 no-paddings">
                    <div class="bg-light-blue tour-content no-paddings">
                        <div class="tour-item tour-detailed-view booking bg-light-blue clearfix">
                            <div class="main-block col-xs-12">
                                
                                <div class="extra-paddings">
                                    <?php if(!$this->item->tour->is_cyclic_tour && !$this->item->tour->is_group_tour): ?>
                                    <div class="price">
                                        <span class="caption">От </span>
                                        <b>
                                            <span class="text-primary"><?php echo number_format( $this->item->tour->nearest_trip->min_adult_main_price, 0, ',', ' ' ); ?>
                                            <span class="fa fa-rub"></span>
                                        </span>
                                        </b> за человека
                                    </div>
                                    <?php endif; ?>
                                    <?php if(!empty($this->selectedDate)): ?>
                                        <div style="margin-top:13px;">Выбранная дата: <b><?php echo $this->selectedDate; ?></b></div>
                                    <?php endif; ?>
                                    <!-- date start -->
                                    <?php if(!$this->item->tour->is_cyclic_tour && !$this->item->tour->is_group_tour): ?>
                                        <div>
                                            <!-- time start -->
                                            <?php if(@!$this->item->tour->is_cyclic_tour && @!$this->item->tour->is_group_tour): ?>
                                                <?php $schedules = $this->item->tour->schedule; ?>
                                                <?php if( !empty($schedules) && !empty($this->selectedDate) ) { ?>
                                                    <span class="tour-info departure clearfix">
                                                        <span class="caption schedules">Время начала:</span>
                                                        <?php foreach( $schedules as $schedule ) { ?>
                                                            <?php if( $schedule->date == $this->selectedDate && $schedule->time == $this->selectedTime): ?>
                                                                <b><?php echo $schedule->time; ?></b>
                                                            <?php elseif($schedule->date == $this->selectedDate): ?>
                                                                <a class="text-underline" href="<?php echo AtomsSiteHelper::createLink($this->slug, array('tour'), array('parent', $this->key), '&date='.$this->selectedDate.'&time='.$schedule->time); ?>">
                                                                    <?php echo $schedule->time; ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php } ?>
                                                    </span>
                                                <?php } ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if(!$this->item->tour->is_cyclic_tour && !$this->item->tour->is_group_tour && isset($this->item->tour->nearest_trip->available_seats)): ?>
                                        <div><b>Свободных мест: <?php echo $this->item->tour->nearest_trip->available_seats; ?></b></div>
                                    <?php endif; ?>
                                    <!-- dates -->
                                    <?php /*if($this->item->tour->is_cyclic_tour && isset($this->item->tour->date_end)): ?>
                                        <a class="text-underline " data-redirect="<?php echo $this->item->tour->links->self ?>" target="_blank" href="<?php $this->item->tour->links->self ?>">
                                            <?php echo $this->item->tour->date_end; ?>
                                        </a>
                                    <?php else*/if(!$this->item->tour->is_cyclic_tour && !$this->item->tour->is_group_tour): ?>
                                        <h3>Все даты</h3>
                                        <?php $schedules = $this->item->tour->schedule; ?>
                                        <?php if( !empty($schedules) && count($schedules) > 1 ): ?>
                                            <span class="tour-info departure clearfix">
                                                <!--<span class="caption schedules">Даты:</span>-->
                                                <?php $tmpScheduleDate = ''; ?>
                                                <?php foreach( $schedules as $schedule ) { ?>
                                                    <?php if($tmpScheduleDate == $schedule->date) continue; ?>
                                                    <?php if( $schedule->date != $this->selectedDate): ?>
                                                            <a class="text-underline" href="<?php echo AtomsSiteHelper::createLink($this->slug, array('tour'), array('parent', $this->key), '&date='.$schedule->date.'&time='.$schedule->time); ?>">
                                                                <?php echo $schedule->date; ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php $tmpScheduleDate = $schedule->date; ?>
                                                <?php } ?>
                                            </span>
                                        <?php else: ?>     
                                            <b><?php echo $this->selectedDate; ?></b>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                                <div class="extra-paddings">
                                    <h3>Цены</h3>
                                    <?php if(!$this->item->tour->is_cyclic_tour && !$this->item->tour->is_group_tour): ?>
                                    <p>
                                      <b class="text-green">
                                        Базовая стоимость на человека в зависимости от варианта размещения
                                      </b>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                <!-- tabs -->
                                <div class="extra-paddings">
                                    <?php if( isset($this->item->tour->nearest_trip->hotels) && count($hotels = $this->item->tour->nearest_trip->hotels) ): ?>
                                    <!--start-->
                                    <div class="text-center hotel-switch">
                                        В этом туре <?php echo count($hotels) ?> гостиницы на выбор:
                                        <br />
                                         <?php // hotels ?>
                                        <div class="cycle-slideshow hotels" data-cycle-fx="fade" data-cycle-timeout="0" data-cycle-slides="> div" data-cycle-prev="#prev" data-cycle-next="#next" data-cycle-speed="1" data-cycle-log="false" data-allow-wrap="false">
                                        <?php foreach($hotels as $k => $hotel) { ?>
                                            <div>
                                                <b><?php echo $hotel->name ?></b>
                                                <!-- stars -->
                                                <span class="text-primary">
                                                    <?php for( $i = 0; $i < $hotel->stars; $i++ ): ?>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    <?php endfor; ?>
                                                </span>
                                            </div>
                                        <?php } ?>      
                                        </div>
                                        <div>
                                            <span id="prev" class="cycle-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></span> 
                                            <span id="custom-caption" class="center"></span>
                                            <span id="next" class="cycle-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                    <?php // price hotels ?>
                                    <div class="cycle-slideshow content" data-cycle-fx="fade" data-cycle-timeout="0" data-cycle-slides="> div" data-cycle-caption="#custom-caption" data-cycle-caption-template="{{slideNum}} из {{slideCount}}" data-cycle-prev="#prev" data-cycle-next="#next" data-cycle-speed="1" data-cycle-log="false" data-allow-wrap="false">
                                        <?php foreach($hotels as $k => $hotel) { ?>
                                            <div>
                                                <!-- content prices hotels -->
                                                <div class="extra-paddings">
                                                    <?php /* start accommodation */ if( isset($hotel->accommodations) ): ?>
                                                        <?php foreach($hotel->accommodations as $aK => $accommodation): ?>
                                                            <div class="" style="padding-bottom:15px;">
                                                                <b><?php echo $accommodation->name ?></b><br/>
                                                                <span class="text-muted"><?php echo $accommodation->type->name ?></span><br/>
                                                                <!-- Adults -->
                                                                <?php if( isset($accommodation->prices->adult_price) ): ?>
                                                                    <div class="price-dotted">
                                                                        <span class="pull-left">Взрослый на основном </span><br class="hidden-md hidden-lg"/>
                                                                        <span class="pull-left">месте</span>
                                                                        <span class="text-primary pull-right"><b>
                                                                        <?php echo number_format( $accommodation->prices->adult_price, 0, ',', ' ' ); ?></b>&nbsp;<span class="rub">&#8381;</span></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                                <?php if( isset($accommodation->prices->adult_extra_bed_price) && !empty($accommodation->extra_beds) ): ?>
                                                                    <?php foreach($accommodation->extra_beds as $kk => $bed): ?>
                                                                            <?php if($bed->age_limit == 0): ?>
                                                                                <div class="price-dotted">
                                                                                    <span class="pull-left">Взрослый на дополнительном </span><br class="hidden-lg" />
                                                                                    <span class="pull-left">месте</span>
                                                                                    <span class="text-primary pull-right"><b><?php echo number_format( $accommodation->prices->adult_extra_bed_price, 0, ',', ' ' ); ?></b> <span class="rub">&#8381;</span></span>
                                                                                </div>
                                                                            <?php break; ?>
                                                                            <?php endif; ?>
                                                                        <?php //endif; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                                
                                                                <!-- Childrens -->
                                                                <?php if( isset($accommodation->prices->age_intervals) ): ?>
                                                                    <?php $tourAgeLimit = (int) $this->item->tour->age_limit; ?>
                                                                    <?php foreach($accommodation->prices->age_intervals as $ageInterval): ?>
                                                                        <?php if( isset($ageInterval->child_main_price) && $tourAgeLimit <= $ageInterval->age ): ?>
                                                                        <div class="price-dotted">
                                                                            <span class="pull-left">Ребенок 
                                                                            <?php echo ( ($tourAgeLimit == $ageInterval->age) ? JHtml::_('duration.duration', $tourAgeLimit, 'y', true, '', ' ') : $tourAgeLimit . '-' . $ageInterval->age . ' лет'); ?> на основном 
                                                                            </span>
                                                                            <br class="hidden-sm hidden-lg" />
                                                                            <span class="pull-left">месте</span>
                                                                            <span class="text-primary pull-right">
                                                                                <b><?php echo number_format( $ageInterval->child_main_price, 0, ',', ' ' ); ?></b>&nbsp;
                                                                                <span class="rub">&#8381;</span>
                                                                            </span>
                                                                          </div>
                                                                          <?php $tourAgeLimit = (int) ($ageInterval->age+1); ?>
                                                                          <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>   
                                                                                                         
                                                                <?php /* сhildrens extra bed */ ?>
                                                                <?php if( @$accommodation->type->id != 1 ): ?>
                                                                    <?php $tourAgeLimit = (int) $this->item->tour->age_limit; ?>
                                                                    <?php foreach($accommodation->prices->age_intervals as $ageInterval): ?>
                                                                        
                                                                        <?php if( isset($ageInterval->child_extra_bed_price) ): ?>
                                                                            <?php $rightBorder = min( $accommodation->extra_bed_max_age_limit,  $ageInterval->age); ?>
                                                                            <?php if( $tourAgeLimit <= $rightBorder ): ?>
                                                                            <div class="price-dotted">
                                                                                <span class="pull-left">
                                                                                  Ребенок <?php echo ( ($tourAgeLimit == $rightBorder) ? JHtml::_('duration.duration', $tourAgeLimit, 'y', true, '', ' ') : $tourAgeLimit . '-' .$rightBorder . ' лет'); ?> на дополнительном
                                                                                </span>
                                                                                <br />
                                                                                <span class="pull-left">месте</span>
                                                                                <span class="text-primary pull-right"><b><?php echo number_format( $ageInterval->child_extra_bed_price, 0, ',', ' ' ); ?></b> <span class="rub">&#8381;</span></span>
                                                                            </div>
                                                                            <?php $tourAgeLimit = (int) ($ageInterval->age+1); ?>
                                                                            <?php endif; ?>
                                                                            
                                                                        <?php endif; ?>
                                                                        
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                                
                                                                <?php /* check extra bed for childrens */ ?>
                                                                <?php if( isset($accommodation->extra_beds) ): ?>
                                                                    <?php //$tourAgeLimit = (int) $this->item->tour->age_limit; ?>
                                                                    <?php $maxAgeLimit = $accommodation->extra_bed_max_age_limit ?>
                                                                        <?php foreach($accommodation->extra_beds as $exb): ?>
                                                                            <?php if($maxAgeLimit >= $tourAgeLimit && $maxAgeLimit != 18 && $exb->age_limit > 0): ?>
                                                                            <div class="price-dotted">
                                                                                <span class="pull-left"> Ребенок 
                                                                                <?php echo ( ($tourAgeLimit == $maxAgeLimit) ? JHtml::_('duration.duration', $maxAgeLimit, 'y', true, '', ' ') : $tourAgeLimit . '-' . $maxAgeLimit . ' лет'); ?> на дополнительном</span><br/>
                                                                                <span class="pull-left">месте</span>
                                                                                <span class="text-primary pull-right"><b>
                                                                                <?php echo number_format( $accommodation->prices->adult_extra_bed_price, 0, ',', ' ' ); ?></b> <span class="rub">&#8381;</span></span>
                                                                            </div>
                                                                            <?php break;endif; ?>
                                                                        <?php endforeach; ?>
                                                                <?php endif; ?>
                                                                
                                                                <?php /* Sell to single */ ?>
                                                                <?php if( isset($accommodation->prices->sell_to_single_price) && $accommodation->sell_to_single): ?>
                                                                <div class="price-dotted">
                                                                    <span class="pull-left">Одноместное размещение</span>
                                                                    <span class="text-primary pull-right"><b><?php  echo number_format( $accommodation->prices->sell_to_single_price, 0, ',', ' ' ); ?></b> <span class="rub">&#8381;</span></span>
                                                                </div> 
                                                                <?php endif; ?>
                                                                <div style="clear:both;"></div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <div style="clear:both;"></div>
                                                    <?php /* end accommodation */ endif; ?>
                                                   
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <br />
                                    </div>
                                    <?php elseif( isset($this->item->tour->nearest_trip->prices) ): ?>
                                        <?php foreach($this->item->tour->nearest_trip->prices as $price): ?>
                                            <div class="price-dotted">
                                                <span class="pull-left"><?php echo $price->name ?></span>
                                                <span class="text-primary pull-right"><b><?php echo $price->price ?></b> <span class="rub">₽</span></span>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div style="clear:both;"></div>
                                    <!--end-->
                                </div>
                                <!-- padding -->
                                <div class="extra-paddings"></div>
                            </div>
                            
                            <!-- btn buy on-line -->
                            <div class="text-center btn-book-wrap">
                                <a href="<?php echo AtomsSiteHelper::createLink($this->slug, array('payment'), array('parent', $this->key), (!empty($this->item->tour->nearest_trip->links->self)?'&path='.$this->item->tour->nearest_trip->links->self:'')); ?>" class="btn btn-primary btn-lg">
                                    <b>Купить онлайн</b>
                                </a>
                            </div>
                            
                            <!-- info slider -->
                            <div class="clearfix">
                                <div class="default-paddings hints">
                                    <div class="text-center">
                                        Отсутствие сборов за бронирование<br />и использование кредитной карты!
                                    </div>
                                    <div class="text-center">
                                        Начните бронировать тур, чтобы увидеть<br />стоимость именно Вашей путевки!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <!-- tour detailed info -->
            <div class="col-sm-7 large-paddings">
                <div class="tour-detailed-view">
                    <!-- tour days -->
                    <h3 style="margin-top:0px;">Программа тура</h3>
                    <div>
                        <?php foreach( $this->item->tour->days as $dataTour ): ?>
                            <div class="day">
                                <div>
                                    <b>
                                        День <?php echo $dataTour->day_number; ?>
                                    </b>
                                
                                    <div class="description tour-description">
                                        <p><?php echo $dataTour->description ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <h3>В базовую стоимость тура включено</h3>
                    <div class="tour-description">
                        <?php echo $this->item->tour->detailed_description->included_services; ?>
                    </div>
                    <h3>В базовую стоимость тура не включено</h3>
                    <div class="tour-description">
                        <?php echo $this->item->tour->detailed_description->not_included_services; ?>
                    </div>
                    <h3>Страховка</h3>
                    <div class="tour-description">
                        <?php echo $this->item->tour->detailed_description->insurance; ?>
                    </div>
                    <h3>Примечания</h3>
                    <div class="tour-description">
                        <?php echo $this->item->tour->detailed_description->tour_notes; ?>
                    </div>
                    <h3>Место сбора группы</h3>
                    <div class="tour-description">
                        <?php foreach( $this->item->tour->pick_up_points as $pickUpPoint ): ?>
                            <div>
                                <?php echo $pickUpPoint->city->name . ', ' . $pickUpPoint->place_description . ' в ' . $pickUpPoint->time; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- hotels -->
                    <?php if( !empty($this->item->tour->nearest_trip->hotels) ): ?>
                        <h3>Гостиницы в этом туре</h3>
                        <?php foreach( $this->item->tour->nearest_trip->hotels as $k => $hotel ): ?>
                            <div id="hotel-<?php echo $hotel->id ?>" class="form-horizontal clearfix">
                            <?php if($k > 0): ?> <hr class="narrow" /> <?php endif; ?>
                                <div class="padding-bottom-15">
                                    
                                    <div class="col-sm-8 no-paddings">
                                        <div class="padding-bottom-15">
                                            <div>
                                                <b><?php echo $hotel->name ?></b>
                                                <?php if($hotel->stars): ?>
                                                    <span class="text-primary">
                                                        <?php for( $i = 0; $i < $hotel->stars; $i++ ): ?>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        <?php endfor; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <?php echo $hotel->city->name; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="hotel-description">
                                            <?php echo $hotel->description; ?>
                                        </div>
                                    </div>
                                    
                                    <?php // slider for hotel ?>
                                    <div class="col-sm-4 no-paddings">
                                        <?php if( isset($hotel->gallery) && !empty($hotel->gallery) ): ?>
                                        <div class="image-container">
                                            <a href="<?php echo $hotel->gallery[0]->original; ?>" data-fancybox="images-preview<?php echo $k; ?>" data-buttons='["thumbs","close"]'>
                                              <img src="<?php echo $hotel->gallery[0]->small; ?>" width="144" height="96" class="small-image" />
                                            </a>
                                            <div class="after">
                                                <span class="zoom">
                                                    <i class="fa fa-search"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div style="display:none;">
                                            <?php foreach( $hotel->gallery as $kImg => $image ): ?>
                                                <?php if($kImg == 0) continue; ?>
                                                <a href="<?php echo $image->original ?>" data-fancybox="images-preview<?php echo $k; ?>" data-thumb="<?php echo $image->small ?>" ></a>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <h3>Возрастные ограничения</h3>
                    <?php echo $this->item->tour->age_limit . '+'; ?>
                </div>
            </div>   
        </div>
        <!-- code for modal -->
        <div id="atoms-basic-modal-content">
            <div>    
                <?php foreach( $this->item->tour->gallery as $image ) { ?>
                    <a rel="album-tour" data-fancybox="gallery" href="<?php echo $image->original ?>" class="image-show"><img src="<?php echo $image->small ?>" /></a>
                <?php } ?>
            </div>
    	</div>
    </div>
</div>