<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Not Found | QR Kuwait</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full p-8 bg-white shadow-xl rounded-2xl text-center border border-gray-100">
        <div class="mb-6 inline-flex items-center justify-center w-20 h-20 bg-red-50 rounded-full">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                </path>
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 italic">QR<span class="text-indigo-600">Kuwait</span></h1>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Store Not Found</h2>
        <p class="text-gray-600 mb-8 leading-relaxed">
            The store <span class="font-mono bg-gray-100 px-2 py-1 rounded text-red-600">"{{ $slug }}"</span> could not
            be found or is currently inactive.
        </p>
        <a href="/"
            class="inline-block bg-indigo-600 text-white font-semibold px-8 py-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            Go to Homepage
        </a>
    </div>
</body>

</html>