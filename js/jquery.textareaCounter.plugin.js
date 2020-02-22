/*
 * jQuery Textarea Characters Counter Plugin v 2.0
 * Examples and documentation at: http://roy-jin.appspot.com/jsp/textareaCounter.jsp
 * Copyright (c) 2010 Roy Jin
 * Version: 2.0 (11-JUN-2010)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Requires: jQuery v1.4.2 or later
 */
(function($){
	$.fn.textareaCount = function(options, fn) {
        var defaults = {
			maxCharacterSize: -1,
			originalStyle: 'originalTextareaInfo',
			warningStyle: 'warningTextareaInfo',
			warningNumber: 0,
			displayFormat: '#input characters | #words words'
		};

		var options = $.extend(defaults, options);

		var container = $(this);

		$("<div class='charleft'>&nbsp;</div>").insertAfter(container);


		//create charleft css
		var charLeftCss = {
			'width' : container.width()
		};

		var charLeftInfo = getNextCharLeftInformation(container);
		charLeftInfo.addClass(options.originalStyle);
		//charLeftInfo.css(charLeftCss);


		var numInput = 0;
		var maxCharacters = options.maxCharacterSize;
		var numLeft = 0;
		var numWords = 0;

		container.bind('keyup', function(event){limitTextAreaByCharacterCount();})
				 .bind('mouseover', function(event){setTimeout(function(){limitTextAreaByCharacterCount();}, 10);})
				 .bind('paste', function(event){setTimeout(function(){limitTextAreaByCharacterCount();}, 10);});

        limitTextAreaByCharacterCount();

		function limitTextAreaByCharacterCount(){
			charLeftInfo.html(countByCharacters());

			//function call back
			if(typeof fn != 'undefined'){
				fn.call(this, getInfo());
			}
			return true;
		}

		function countByCharacters(){
			var content = container.val();
			var contentLength = content.replace(/\s/g, "").length;

			var term = "\\s*(?:\\S\\s*){0,"+options.maxCharacterSize+"}"; // RegEx - unlimited spaces, characters up to set character limit
			var rgxp = new RegExp(term, "g");  // query for characters to character limit, excluding spaces
			var xxx = content.match(rgxp) ? content.match(rgxp) : ' ' ; //  get characters to amount
			var rgxp2 = new RegExp(" ", "g");  // query to count spaces in set amount
			var contentSpaceCount = String(xxx).match(rgxp2) ? String(xxx).match(rgxp2).length : 0;   // count spaces in amount
			var rgxp3 = new RegExp("[\r\n]", "g");  // query to count line breaks in set amount
			var contentLineBreakCount = String(xxx).match(rgxp3) ? String(xxx).match(rgxp3).length : 0;   // count spaces in amount
			//Start Cut
			if(options.maxCharacterSize > 0){
			var num = options.maxCharacterSize + contentSpaceCount + contentLineBreakCount; // add space and character count to the max character size to allow them to be excluded from the count
				//If copied content is already more than maxCharacterSize, chop it to maxCharacterSize.
				if(contentLength >= options.maxCharacterSize) {
					content = content.substring(0, options.maxCharacterSize + contentSpaceCount + contentLineBreakCount);
				}

				var newlineCount = getNewlineCount(content);

				// newlineCount new line character. For windows, it occupies 2 characters
				var systemmaxCharacterSize = options.maxCharacterSize - newlineCount;
				if (!isWin()){
					 systemmaxCharacterSize = options.maxCharacterSize
				}
				if(contentLength > systemmaxCharacterSize ){
					//avoid scroll bar moving
					var originalScrollTopPosition = this.scrollTop;
					container.val(content.substring(0, systemmaxCharacterSize + contentSpaceCount + contentLineBreakCount));
					this.scrollTop = originalScrollTopPosition;
				}
				charLeftInfo.removeClass(options.warningStyle);
				if(systemmaxCharacterSize - contentLength <= options.warningNumber){
					charLeftInfo.addClass(options.warningStyle);
				}

				numInput = container.val().replace(/\s/g, "").length + newlineCount;
				if(!isWin()){
					numInput = container.val().replace(/\s/g, "").length;
				}

				numWords = countWord(getCleanedWordString(container.val()));

				numLeft = maxCharacters - numInput;
			} else {
				//normal count, no cut
				var newlineCount = getNewlineCount(content);
				numInput = container.val().replace(/\s/g, "").length + newlineCount;
				if(!isWin()){
					numInput = container.val().replace(/\s/g, "").length;
				}
				numWords = countWord(getCleanedWordString(container.val()));
			}

			return formatDisplayInfo();
		}

		function formatDisplayInfo(){
			var format = options.displayFormat;
			format = format.replace('#input', numInput);
			format = format.replace('#words', numWords);
			//When maxCharacters <= 0, #max, #left cannot be substituted.
			if(maxCharacters > 0){
				format = format.replace('#max', maxCharacters);
				format = format.replace('#left', numLeft);
			}
			return format;
		}

		function getInfo(){
			var info = {
				input: numInput,
				max: maxCharacters,
				left: numLeft,
				words: numWords
			};
			return info;
		}

		function getNextCharLeftInformation(container){
				return container.next('.charleft');
		}

		function isWin(){
			var strOS = navigator.appVersion;
			if (strOS.toLowerCase().indexOf('win') != -1){
				return true;
			}
			return false;
		}

		function getNewlineCount(content){
			var newlineCount = 0;
			for(var i=0; i<content.length;i++){
				if(content.charAt(i) == '\n'){
					newlineCount++;
				}
			}
			return 0;
		}

		function getCleanedWordString(content){
			var fullStr = content + " ";
			var initial_whitespace_rExp = /^[^A-Za-z0-9]+/gi;
			var left_trimmedStr = fullStr.replace(initial_whitespace_rExp, "");
			var non_alphanumerics_rExp = rExp = /[^A-Za-z0-9]+/gi;
			var cleanedStr = left_trimmedStr.replace(non_alphanumerics_rExp, " ");
			var splitString = cleanedStr.split(" ");
			return splitString;
		}

		function countWord(cleanedWordString){
			var word_count = cleanedWordString.length-1;
			return word_count;
		}
	};
})(jQuery);