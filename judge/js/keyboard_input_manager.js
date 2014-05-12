function KeyboardInputManager() {
    this.events = {};

    if (window.navigator.msPointerEnabled) {
        //Internet Explorer 10 style
        this.eventTouchstart    = "MSPointerDown";
        this.eventTouchmove     = "MSPointerMove";
        this.eventTouchend      = "MSPointerUp";
    } else {
        this.eventTouchstart    = "touchstart";
        this.eventTouchmove     = "touchmove";
        this.eventTouchend      = "touchend";
    }

    this.listen();
}

KeyboardInputManager.prototype.on = function (event, callback) {
    if (!this.events[event]) {
        this.events[event] = [];
    }
    this.events[event].push(callback);
};

KeyboardInputManager.prototype.emit = function (event, data) {
    var callbacks = this.events[event];
    if (callbacks) {
        callbacks.forEach(function (callback) {
            callback(data);
        });
    }
};

function sleep(millis, callback) {
    setTimeout(function()
               { callback(); }
               , millis);
}

KeyboardInputManager.prototype.listen = function () {
    var self = this;

    var map = {
        38: 0, // Up
        39: 1, // Right
        40: 2, // Down
        37: 3, // Left
    };

    var lock = 0;

    document.addEventListener("keydown", function (event) {
        var modifiers = event.altKey || event.ctrlKey || event.metaKey ||
            event.shiftKey;
        var mapped    = map[event.which];

        if (lock === 1) return;
        if (!modifiers) {
            if (mapped !== undefined) {
                event.preventDefault();
                lock = 1;
                self.emit("move", mapped);
                sleep(800, function () {
                    lock = 0;
                    self.emit("auto");
                });
            }

            if (event.which === 32) self.restart.bind(self)(event);
        }
    });

    var retry = document.querySelector(".retry-button");
    retry.addEventListener("click", this.restart.bind(this));
    retry.addEventListener(this.eventTouchend, this.restart.bind(this));

    var keepPlaying = document.querySelector(".keep-playing-button");
    keepPlaying.addEventListener("click", this.keepPlaying.bind(this));
    keepPlaying.addEventListener("touchend", this.keepPlaying.bind(this));

    // Listen to swipe events
    var touchStartClientX, touchStartClientY;
    var gameContainer = document.getElementsByClassName("game-container")[0];

    gameContainer.addEventListener(this.eventTouchstart, function (event) {
        if (( !window.navigator.msPointerEnabled && event.touches.length > 1) || event.targetTouches > 1) return;
        
        if(window.navigator.msPointerEnabled){
            touchStartClientX = event.pageX;
            touchStartClientY = event.pageY;
        } else {
            touchStartClientX = event.touches[0].clientX;
            touchStartClientY = event.touches[0].clientY;
        }
        
        event.preventDefault();
    });

    gameContainer.addEventListener(this.eventTouchmove, function (event) {
        event.preventDefault();
    });

    gameContainer.addEventListener(this.eventTouchend, function (event) {
        if (( !window.navigator.msPointerEnabled && event.touches.length > 0) || event.targetTouches > 0) return;

        var touchEndClientX, touchEndClientY;
        if(window.navigator.msPointerEnabled){
            touchEndClientX = event.pageX;
            touchEndClientY = event.pageY;
        } else {
            touchEndClientX = event.changedTouches[0].clientX;
            touchEndClientY = event.changedTouches[0].clientY;
        }

        var dx = touchEndClientX - touchStartClientX;
        var absDx = Math.abs(dx);

        var dy = touchEndClientY - touchStartClientY;
        var absDy = Math.abs(dy);

        if (Math.max(absDx, absDy) > 10) {
            lock = 1;
            // (right : left) : (down : up)
            self.emit("move", absDx > absDy ? (dx > 0 ? 1 : 3) : (dy > 0 ? 2 : 0));
            sleep(500, function () {
                lock = 0;
                self.emit("auto");
            });
        }
    });
};

KeyboardInputManager.prototype.restart = function (event) {
    event.preventDefault();
    this.emit("restart");
};

KeyboardInputManager.prototype.keepPlaying = function (event) {
    event.preventDefault();
    this.emit("restart");
};
