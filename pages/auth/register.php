<?php ?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>


<body class="flex items-center justify-center">
<form bindsubmit="" class="bg-white p-10 shadow-lg rounded-lg flex flex-col gap-5 items-center justify-center w-full max-w-xl">
    <h1 class="text-xl font-semibold text-center" style="color:#102b48">Welcome to Lostify <br/> <span class="text-md p-4 text-gray-400">Create an account to get started</span></h1>
    <div class="flex flex-col items-start justify-center  gap-2 w-full">
     <p>Enter your name</p>   
    <input type="text" 
       class="bg-white bg-opacity-40 w-full rounded-md px-5 py-3 ring-1 focus:ring-2 ring-blue-600 focus:outline-none border-blue-600 text-black" 
       placeholder="Enter your name" />

    </div>
    <div class="flex flex-col items-start justify-center  gap-2 w-full">
     <p>Enter your email</p>   
    <input type="email" 
       class="bg-white bg-opacity-40 w-full rounded-md px-5 py-3 ring-1 focus:ring-2 ring-blue-600 focus:outline-none border-blue-600 text-black" 
       placeholder="Enter your email" />

    </div>
    <div class="flex flex-col items-start justify-center  gap-2 w-full">
     <p>Enter your password</p>   
    <input type="password" 
       class="bg-white bg-opacity-40 w-full rounded-md px-5 py-3 ring-1 focus:ring-2 ring-blue-600 focus:outline-none border-blue-600 text-black" 
       placeholder="Enter your password" />

    </div>
    <button class="w-full flex items-center text-white justify-center rounded-md px-5 py-3" style="background-color: #102b48;">Register</button>
    <a href="" class="text-md text-gray-600">already have an account <span  style="color: #102b48;">Login</span></a>
                
    
    
</form>
    
</body>