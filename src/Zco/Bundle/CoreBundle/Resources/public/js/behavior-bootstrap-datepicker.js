/**
 * @provides vitesse-behavior-bootstrap-datepicker
 * @requires vitesse-behavior
 *           jquery-no-conflict
 *           @ZcoCoreBundle/Resources/public/js/vendor/bootstrap-datepicker.js
 */
Behavior.create('bootstrap-datepicker', function(config)
{
	jQuery('#' + config.id).datepicker();
});