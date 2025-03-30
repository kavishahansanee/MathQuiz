// Modified version of code by Kenny Yip

(function() {
    var errors = 0;
    var maxErrors = 15; // Maximum allowed errors
    var cardList = [ "images/apple","images/pineapple", "images/grapes", "images/tomato", "images/papaya", "images/orang", "images/mango", "images/duriyan", "images/strawberry", "images/watermelon"]
    
    
    var cardSet;
    var board = [];
    var rows = 4;
    var columns =5;

    var matchedCards = [];
    var totalCards = rows * columns;
    
    var card1Selected;
    var card2Selected;

    document.addEventListener('DOMContentLoaded', function() {
      
        window.onload = function() {
            shuffleCards();
            startGame();
        }
        
        function shuffleCards() {
            cardSet = cardList.concat(cardList); //two of each card
            console.log(cardSet);
            //shuffle
            for (let i = 0; i < cardSet.length; i++) {
                let j = Math.floor(Math.random() * cardSet.length); //get random index
                //swap
                let temp = cardSet[i];
                cardSet[i] = cardSet[j];
                cardSet[j] = temp;
            }
            console.log(cardSet);
        }
 
        function startGame() {
            //arrange the board 4x5
            for (let r = 0; r < rows; r++) {
                let row = [];
                for (let c = 0; c < columns; c++) {
                    let cardImg = cardSet.pop();
                    row.push(cardImg); 
        
                    let card = document.createElement("img");
                    card.id = r.toString() + "-" + c.toString();
                    card.src = cardImg + ".jpg";
                    card.classList.add("card");
                    card.addEventListener("click", selectCard);
                    document.getElementById("board").append(card);
        
                }
                board.push(row);
            }
        
            console.log(board);
            setTimeout(hideCards, 1000);
        }
        
        function hideCards() {
            for (let r = 0; r < rows; r++) {
                for (let c = 0; c < columns; c++) {
                    let card = document.getElementById(r.toString() + "-" + c.toString());
                    card.src = "images/back.jpg";
                }
            }
        }
        
        function selectCard() {
        
            if (this.src.includes("back")) {
                if (!card1Selected) {
                    card1Selected = this;
        
                    let coords = card1Selected.id.split("-"); 
                    let r = parseInt(coords[0]);
                    let c = parseInt(coords[1]);
        
                    card1Selected.src = board[r][c] + ".jpg";
                }
                else if (!card2Selected && this != card1Selected) {
                    card2Selected = this;
        
                    let coords = card2Selected.id.split("-"); 
                    let r = parseInt(coords[0]);
                    let c = parseInt(coords[1]);
        
                    card2Selected.src = board[r][c] + ".jpg";
                    setTimeout(update, 1000);
                }
            }
        
        }
 
        function update() {
          if (card1Selected.src !== card2Selected.src) {
              card1Selected.src = "images/back.jpg";
              card2Selected.src = "images/back.jpg";
              errors += 1;
              document.getElementById("errors").innerText = errors;

              if (errors >= maxErrors) {
                  // Display a message when the maximum errors are reached
                  alert("Please match all the cards within 15 tries. Try again!");

                  resetGame();
              }
          } else {
              matchedCards.push(card1Selected.src); // Assuming card1Selected.src uniquely identifies the card
              matchedCards.push(card2Selected.src);

              if (matchedCards.length === totalCards) {
                  // All cards matched successfully
                  document.getElementById("submitBtn").disabled = false;
              }
          }

          card1Selected = null;
          card2Selected = null;
      }

      function resetGame() {
        errors = 0;
        document.getElementById("errors").innerText = errors;

        // Remove existing cards
        var boardElement = document.getElementById("board");
        while (boardElement.firstChild) {
            boardElement.removeChild(boardElement.firstChild);
        }
        shuffleCards();
        startGame();
    }

        // Event listener for the Submit button
        document.getElementById("submitBtn").addEventListener("click", function () {
        var uniqueMatchedCards = [...new Set(matchedCards)]; // Get unique elements from matchedCards

        if (uniqueMatchedCards.length === totalCards / 2) {
            window.location.href = "quiznew.php";
        } else {
            // Display a message if the user tries to submit without matching all cards
            alert("You must match all the cards before submitting.");
        }
        });

         console.log('DOM is ready!');
        });
      })();



 
