			var notificationTimeout;
			//Shows updated notification popup 
			var updateNotification = function(task, notificationText, newClass){
				var notificationPopup = $('.notification-popup ');
				notificationPopup.find('.task').text(task);
				notificationPopup.find('.notification-text').text(notificationText);
				notificationPopup.removeClass('hide success');
			// If a custom class is provided for the popup, add It
			if(newClass)
				notificationPopup.addClass(newClass);
			// If there is already a timeout running for hiding current popup, clear it.
			if(notificationTimeout)
				clearTimeout(notificationTimeout);
			// Init timeout for hiding popup after 3 seconds
			notificationTimeout = setTimeout(function(){
				notificationPopup.addClass('hide');
			}, 3000);
		}