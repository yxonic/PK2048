// game_manager.js

// [CHANGE] aadStartTiles: add 2, 4, 4, 2 in the middle
GameManager.prototype.addStartTiles = function() {
    this.addTile(1, 1, 2);
    this.addTile(1, 2, 4);
    this.addTile(2, 1, 4);
    this.addTile(2, 2, 2);
}

// Add a specific tile
GameManager.prototype.addTile = function(x, y, value) {
    var tile = new Tile({x : x, y : y}, value);
    
    this.grid.insertTile(tile);
}

// Adds a tile in a random position, 2 and 4 equally possible
GameManager.prototype.addRandomTile = function () {
  if (this.grid.cellsAvailable()) {
    var value = Math.random() < 0.5 ? 2 : 4;
    var tile = new Tile(this.grid.randomAvailableCell(), value);

    this.grid.insertTile(tile);
  }
};
