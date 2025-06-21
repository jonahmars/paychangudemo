<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pay Now</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
  <div class="bg-white shadow-lg rounded-2xl p-6 w-full max-w-sm">
    <h2 class="text-xl font-bold text-center text-gray-800 mb-2">Secure Payment</h2>
    <p class="text-sm text-gray-500 text-center mb-6">Complete your payment using one of the available methods</p>

    <form action="process_payment.php" method="POST" class="space-y-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input type="text" id="name" name="name" required placeholder="John Banda"
          class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 outline-none"/>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
        <input type="email" id="email" name="email" required placeholder="john@example.com"
          class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 outline-none"/>
      </div>

      <div>
        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (MWK)</label>
        <input type="number" id="amount" name="amount" required placeholder=""
          class="mt-1 w-full px-3 py-2 pl-12 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 outline-none relative"/>
        
      </div>

      <div>
       
        
      </div>

      <button type="submit"
        class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
        Pay Now
      </button>
    </form>

    <div class="text-xs text-gray-400 text-center mt-4 flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 1 3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
      </svg>
      All payments are secure and encrypted
    </div>
  </div>
</body>
</html>


