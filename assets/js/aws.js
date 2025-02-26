// AWS Code to fetch and update the view count of the website
const counter = document.querySelector(".counter-number");

// Function to fetch and update the visitor count
async function updateCounter() {
  try {
    let response = await fetch("https://nrssqz6l25fk26efqunamso6ce0euran.lambda-url.us-east-1.on.aws/");

    console.log("Raw Response:", response); // Debug network response

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    let textData = await response.text(); // Read response as plain text
    let count = parseInt(textData, 10); // Convert text to number

    console.log("Parsed Count:", count); // Debug parsed data

    // Update the counter element only if data is a valid number
    if (!isNaN(count)) {
      counter.innerHTML = `Views : ${count}`;
    } else {
      throw new Error("Invalid number format received from the server.");
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
