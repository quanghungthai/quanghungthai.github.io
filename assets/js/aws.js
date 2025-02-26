// AWS Code to fetch and update the view count of the website
const counter = document.querySelector(".counter-number");

// Function to fetch and update the visitor count
async function updateCounter() {
  try {
    let response = await fetch("https://nrssqz6l25fk26efqunamso6ce0euran.lambda-url.us-east-1.on.aws/", {
      method: "GET",
      headers: {
        "Content-Type": "application/json"
      }
    });

    // Check if the response is successful
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    let data = await response.json();

    // Update the counter element only if data is valid
    if (data && typeof data.count === "number") {
      counter.innerHTML = `Views : ${data.count}`;
    } else {
      throw new Error("Invalid data format received from the server.");
    }
  } catch (error) {
    console.error("Error fetching view count:", error);
    counter.innerHTML = "Views : Unable to load";
  }
}

// Run the function immediately on page load
updateCounter();

// Refresh the counter every 5 seconds for real-time updates
setInterval(updateCounter, 5000);
