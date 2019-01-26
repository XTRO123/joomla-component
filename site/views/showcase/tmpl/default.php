<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 3 or later; see LICENSE.txt
*/

/** @var $this AtomsViewShowcase */
defined( '_JEXEC' ) or die; // No direct access

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

?>
<div class="item-page <?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
    
    <div id="tours" class="col-md-12 small-paddings">
        <?php foreach( $this->items->tour_schedules as $tour ): ?>
            <div class="tour-item search-item clearfix">
                <!-- label image tour -->
                <div class="tour-img">
                    <a data-redirect="<?php echo AtomsSiteHelper::createLink($tour->tour->slug, array('tour'), array('parent', $this->key), (isset($tour->date)?'&date='.$tour->date:'').(isset($tour->time)?'&time='.$tour->time:'') ); ?>" target="_blank" href="<?php echo AtomsSiteHelper::createLink($tour->tour->slug, array('tour'), array('parent', $this->key), (isset($tour->date)?'&date='.$tour->date:'').(isset($tour->time)?'&time='.$tour->time:'') ); ?>">
                        <img src="<?php echo $tour->tour->gallery[0]->medium ?>" class="tour-img" alt="<?php echo $tour->tour->name; ?>" />
                    </a>
                 </div>
                 <!-- content tour -->
                 <div class="tour-info-wrap">
                    <div class="tour-row">
                        <div class="main-block col-xs-12">
                            <!-- description tour -->
                            <div class="top-line">
                                <b class="text-primary"><?php echo $tour->tour->age_limit; ?>+</b>
                                <span><?php echo $tour->tour->duration_type . ' ' . $tour->tour->type ?> тур</span>
                            </div>
                            <!-- name tour -->
                            <div class="tour-name">
                                <a data-redirect="<?php echo AtomsSiteHelper::createLink($tour->tour->slug, array('tour'), array('parent', $this->key) ); ?>" target="_blank" href="<?php echo AtomsSiteHelper::createLink($tour->tour->slug, array('tour'), array('parent', $this->key) ); ?>"><?php echo $tour->tour->name; ?></a>
                            </div>
                            
                            <!-- labels: types tours / booking kind / hotels -->
                            <div class="tour-labels">
                                <!-- booking kind -->
                                <?php if( isset($tour->booking_kind) && $tour->booking_kind == "instant_booking" ): ?>
                                <span class="label label-scarlet" data-toggle="tooltip" data-placement="bottom" title="Места уже подтверждены! Этот тур можно приобрести без дополнительного согласования." data-original-title="Места уже подтверждены! Этот тур можно приобрести без дополнительного согласования.">Мгновенная покупка</span>
                                <?php endif; ?>
                                <!-- next tours -->
                                <?php if($tour->tour->is_group_tour): ?>
                                    <span class="label label-yellow" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Тур для организованных групп: школ, корпоративных клиентов и других.">Для групп</span>
                                <?php endif ?>
                                <?php if($tour->tour->is_reception_tour): ?>
                                    <span class="label label-gray" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Тур типа Приём. Вы добираетесь до места начала тура самостоятельно.">Приём</span>
                                <?php endif; ?>
                                <?php if($tour->tour->is_departure_tour): ?>
                                    <span class="label label-gray" data-toggle="tooltip" data-placement="bottom" title="Тур типа Выезд. Вы вместе с экскурсионной группой выезжаете на автобусе из своего города и двигаетесь к цели путешествия." data-original-title="Тур типа Выезд. Вы вместе с экскурсионной группой выезжаете на автобусе из своего города и двигаетесь к цели путешествия.">Выезд</span>
                                <?php endif; ?>
                                <?php if($tour->tour->is_cyclic_tour): ?>
                                    <span class="label label-green" data-toggle="tooltip" data-placement="bottom" title="В данном туре можно самостоятельно выбрать продолжительность и дату заезда." data-original-title="В данном туре можно самостоятельно выбрать продолжительность и дату заезда.">Тур-конструктор</span>
                                <?php endif; ?>
                                
                                <!-- hotels -->
                                <?php if( isset($tour->hotels_number) && $tour->hotels_number > 1 ): ?>
                                    <?php $hotels = $tour->hotels_number; ?>
                                    <span class="label label-gray" data-toggle="tooltip" data-placement="bottom" title="Для этого тура предусмотрено несколько гостиниц. От выбора гостиницы будет зависеть стоимость тура." data-original-title="Для этого тура предусмотрено несколько гостиниц. От выбора гостиницы будет зависеть стоимость тура."><?php echo $hotels; ?> гостиницы</span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- places -->
                            <div class="tour-info tour-cities">
                                <span class="caption">Места показа: </span>
                                <span>
                                    <?php 
                                    foreach( $tour->tour->route_cities_list as $city ) {
                                        if( $city == end($tour->tour->route_cities_list) )
                                            echo $city;
                                        else
                                            echo $city . ', ';
                                    } 
                                    ?>
                                </span>
                            </div>
                            
                            <!-- start and over place -->
                            <div class="tour-info tour-cities">
                                <span class="caption">Город начала<?php if(isset($tour->tour->end_city)): ?> / завершения<?php endif; ?>: </span>
                                <span><?php echo $tour->tour->start_city . (isset($tour->tour->end_city) ? ' / ' . $tour->tour->end_city : ''); ?></span>
                            </div>
                            
                            <!-- duration -->
                            <div class="tour-info tour-duration">
                                <!-- hours -->
                                <?php if( isset($tour->tour->duration_hours) ): ?>
                                    <span class="caption">Длительность:</span>
                                    <b><?php echo JHtml::_('duration.duration', $tour->tour->duration_hours, 'h'); ?></b>
                                <!-- days -->
                                <?php else: ?> 
                                    <span class="caption">Длительность:</span>
                                    <!-- duration -->
                                    <b><?php echo (@$tour->tour->is_cyclic_tour) ? JHtml::_('duration.duration', $tour->tour->duration_days, 'd', false, '—', '') : JHtml::_('duration.duration', $tour->tour->duration_days, 'd', true, ' / '); ?><?php echo JHtml::_('duration.duration', $tour->tour->duration_nights, 'n'); ?></b>
                                <?php endif; ?>
                            </div>
                            
                            <!-- dates -->
                            <?php if(@$tour->tour->is_cyclic_tour): ?>
                                <span class="tour-info departure clearfix">
                                    <span class="caption schedules">Даты:</span>
                                    <b><?php echo $tour->date; ?>—<?php echo $tour->date_end; ?></b>
                                </span>
                            <?php elseif(@!$tour->tour->is_cyclic_tour && @!$tour->is_group_tour): ?>
                                
                                <?php $schedules = $tour->tour->schedule; ?>
                                <?php if( !empty($schedules) && !empty($tour->date) ) { ?>
                                    <span class="tour-info departure clearfix">
                                        <span class="caption schedules">Даты:</span>
                                        <?php $tmpScheduleDate = ''; ?>
                                        <?php foreach( $schedules as $schedule ) { ?>
                                            <?php if($tmpScheduleDate == $schedule->date) continue; ?>
                                            <?php if( $schedule->date == $tour->date): ?>
                                                    <b><?php echo $schedule->date; ?></b>
                                                <?php else: ?>
                                                    <a class="text-underline" href="<?php echo AtomsSiteHelper::createLink($tour->tour->slug, array('tour'), array('parent', $this->key), '&date='.$schedule->date.'&time='.$schedule->time); ?>">
                                                        <?php echo $schedule->date; ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php $tmpScheduleDate = $schedule->date; ?>
                                        <?php } ?>
                                    </span>
                                <?php } ?>
                                
                                <!-- time start -->
                                <?php if( !empty($schedules) && !empty($tour->time) ) { ?>
                                    
                                    <span class="tour-info departure clearfix">
                                        <span class="caption schedules">Время начала:</span>
                                        <?php foreach( $schedules as $schedule ) { ?>
                                            <?php if( $schedule->date == $tour->date && $schedule->time == $tour->time): ?>
                                                <b><?php echo $schedule->time; ?></b>
                                            <?php elseif($schedule->date == $tour->date): ?>
                                                <a class="text-underline" href="<?php echo AtomsSiteHelper::createLink($tour->tour->slug, array('tour'), array('parent', $this->key), '&date='.$tour->date.'&time='.$schedule->time); ?>">
                                                    <?php echo $schedule->time; ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php } ?>
                                    </span>
                                <?php } ?>
                                
                            <?php endif; ?>
                                                        
                            <!-- price -->
                            <div class="tour-info tour-info-default price-line">
                                <div class="price">
                                    <span class="caption">От</span>
                                    <b><span class="text-primary">
                                        <?php echo ($tour->tour->is_group_tour) ? number_format( $tour->tour->group_price->price, 0, ',', ' ' ) : number_format( $tour->min_adult_main_price, 0, ',', ' ' ); ?>
                                        <span class="fa fa-rub"></span>
                                    </span></b>
                                    <!-- count for price -->
                                    <?php if( $tour->tour->is_group_tour ): ?>
                                        <span class="caption"> за <?php echo $tour->tour->group_price->name; ?></span>
                                    <?php elseif($tour->tour->is_cyclic_tour): ?>
                                        <span class="caption"> за человека за <?php echo JHtml::_('duration.duration', $tour->tour->duration_days, 'n', true, '', ' ') ?></span>
                                    <?php else: ?>
                                        <span class="caption"> за человека</span>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if( !$tour->tour->is_group_tour && isset($tour->seats_quote) ): ?>
                                    <?php $seatsQuote = $tour->seats_quote; ?>
                                    <div style="display: inline-block;">
                                        <div class="free-space-bulb <?php echo (($seatsQuote>0)?'green':'red'); ?>"></div>
                                        <span><?php echo (($seatsQuote>0)?'Места есть':'Мест нет'); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <a class="btn btn-primary pull-right" target="_blank" href="<?php echo AtomsSiteHelper::createLink($tour->tour->slug, array('tour'), array('parent', $this->key), (isset($tour->date)?'&date='.$tour->date:'').(isset($tour->time)?'&time='.$tour->time:'') ); ?>"><b>Подробнее</b></a>
                            </div>
                                
                        </div>
                    </div>
                 </div> 
            </div>
        <?php endforeach; ?>
    </div>
</div>