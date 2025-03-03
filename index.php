<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="w-full  flex flex-col ">
    <nav class="flex flex-row items-center w-full justify-between py-3 px-4 md:px-20 sticky top-0 navbar"
        style="background-color: #102b48;">
        <h1 class="text-xl  font-semibold">Lostify</h1>
        <!-- Centered desktop navigation -->
        <ul class="hidden md:flex gap-10 absolute left-1/2 transform -translate-x-1/2">
            <li class=" text-md"><a href="">Home</a></li>
            <li class=" text-md"><a href="">How it Works</a></li>
            <li class="text-md"><a href="">Features</a></li>
            <li class="text-md"><a href="">Testimonials</a></li>
        </ul>
        <div class="md:hidden  text-3xl cursor-pointer" onclick="toggleMenu()">
            ☰
        </div>
        <button class="text-white bg-blue-400 px-6 py-3 rounded-md hover:bg-blue-500 hidden md:flex">
            Get Started
        </button>
    </nav>
    <!-- Centered mobile menu -->
    <ul class="menu hidden flex-col items-center justify-center gap-10  text-white w-full px-5 py-2 md:hidden text-center"
        style="background-color: #102b48;">
        <li class="w-full py-2"><a href="#">Home</a></li>
        <li class="w-full py-2"><a href="#">How it Works</a></li>
        <li class="w-full py-2"><a href="#">Features</a></li>
        <li class="w-full py-2"><a href="#">Testimonials</a></li>
        <li class="w-full flex justify-center">
            <button class="text-white bg-blue-400 px-6 py-3 rounded-md hover:bg-blue-500">
                Get Started
            </button>
        </li>
    </ul>
    <div class="bg-gray-100 items-start  flex flex-row  justify-between px-20 py-20">
        <div class="flex flex-col gap-6 items-start justify-center">
            <h1 class="text-4xl w-full max-w-xl font-bold" style="color: #102b48;">Have anything lost or found Let's
                Connect</h1>
            <p class="text-md w-full max-w-xl">With the use of our platform one is able to get and find his lost
                property through the use of our live tool and ai which will help me to scan different materials and be
                able to view his lost property and document</p>
            <div class="flex flex-row flex-wrap md:flex-nowrap md:gap-10 gap-5">
                <button class="w-full flex items-center text-white justify-center rounded-md px-5 py-2"
                    style="background-color: #102b48;">Report Lost item</button>
                <button
                    style="width: 100%; display: flex; align-items: center;text-wrap:nowrap; justify-content: center; padding: 10px 20px; border: 2.5px solid #102b48; color: #102b48; border-radius: 6px; background-color: transparent; cursor: pointer;">
                    Report found item
                </button>



            </div>
        </div>
        <img src="./assets/images/lost.png" alt="hero" class="w-1/3 h-1/3 md:contain hidden md:flex">

    </div>
    <div class="px-20 py-20 flex flex-col items-center justify-center gap-20">
        <div class="flex flex-col gap-8 items-center justify-center">
            <div class="bg-blue-200 items-center justify-center px-6 py-2 rounded-full">
                <p class="text-blue-600 font-semibold text-md">simple process</p>

            </div>
            <h1 style="color: #102b48;" class="text-4xl font-bold">How lostify Works</h1>
            <p class="text-md w-full max-w-2xl text-center ">Our streamlined three-step process makes it simple to
                reconnect
                lost documents with their owners through the power of AI.</p>

        </div>
        <div class="flex w-full justify-between gap-5 xl:justify-center xl:gap-28 items-center">
            <div
                class="flex p-5 items-center justify-center rounded-full  bg-white shadow-lg hover:shadow-xl hover:transition duration-500 hover:bottom-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-upload text-lightBlue" data-lov-id="src/components/HowItWorks.tsx:17:10"
                    data-lov-name="Upload" data-component-path="src/components/HowItWorks.tsx" data-component-line="17"
                    data-component-file="HowItWorks.tsx" data-component-name="Upload"
                    data-component-content="%7B%22className%22%3A%22text-lightBlue%22%7D">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" x2="12" y1="3" y2="15"></line>
                </svg>
            </div>
            <div class="flex flex-col p-5 rounded-md items-start justify-center shadow-lg gap-2">
                <div class="flex bg-blue-200 px-4 py-2 items-center text-blue-600 justify-center rounded-full">
                    1
                </div>
                <h2 style="color: #102b48;" class="text-2xl font-bold">Upload Document</h1>
                    <p style="w-full max-w-xl text-md">Upload a clear photo of the lost or found document to our secure
                        platform.</p>

            </div>

        </div>
        <div class="flex w-full flex-row-reverse gap-5 justify-between xl:justify-center xl:gap-28 items-center">
            <div
                class="flex p-5 items-center  justify-center rounded-full  bg-white shadow-lg hover:shadow-xl hover:transition duration-500 hover:bottom-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-scan text-lightBlue" data-lov-id="src/components/HowItWorks.tsx:23:10"
                    data-lov-name="Scan" data-component-path="src/components/HowItWorks.tsx" data-component-line="23"
                    data-component-file="HowItWorks.tsx" data-component-name="Scan"
                    data-component-content="%7B%22className%22%3A%22text-lightBlue%22%7D">
                    <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                    <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                    <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                    <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                </svg>
            </div>
            <div class="flex flex-col p-5 rounded-md items-start justify-center shadow-lg gap-2">
                <div class="flex bg-blue-200 px-4 py-2 items-center text-blue-600 justify-center rounded-full">
                    2
                </div>
                <h2 style="color: #102b48;" class="text-2xl font-bold">Ai scans & Matches</h1>
                    <p style="w-full max-w-xl text-md">Our advanced AI analyzes the document and compares it with our
                        database for potential matches.</p>

            </div>

        </div>
        <div class="flex w-full justify-between gap-5 xl:justify-center xl:gap-28 items-center">
            <div
                class="flex p-5 items-center justify-center rounded-full  bg-white shadow-lg hover:shadow-xl hover:transition duration-500 hover:bottom-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-message-square text-lightBlue"
                    data-lov-id="src/components/HowItWorks.tsx:29:10" data-lov-name="MessageSquare"
                    data-component-path="src/components/HowItWorks.tsx" data-component-line="29"
                    data-component-file="HowItWorks.tsx" data-component-name="MessageSquare"
                    data-component-content="%7B%22className%22%3A%22text-lightBlue%22%7D">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </div>
            <div class="flex flex-col p-5 rounded-md items-start justify-center shadow-lg gap-2">
                <div class="flex bg-blue-200 px-4 py-2 items-center text-blue-600 justify-center rounded-full">
                    3
                </div>
                <h2 style="color: #102b48;" class="text-2xl font-bold">Connect with owner/finder</h1>
                    <p style="w-full max-w-xl text-md">Once a match is found, communicate securely through our platform
                        to arrange document return.</p>

            </div>

        </div>

    </div>
    <div class="px-20 py-20 flex flex-col items-center justify-center gap-20 bg-gray-100">
        <div class="flex flex-col gap-8 items-center justify-center">
            <div class="bg-blue-200 items-center justify-center px-6 py-2 rounded-full">
                <p class="text-blue-600 font-semibold text-md">Key Features</p>

            </div>
            <h1 style="color: #102b48;" class="text-4xl font-bold">Why Choose Lostify</h1>
            <p class="text-md w-full max-w-2xl text-center ">Our cutting-edge technology and thoughtful features make
                recovering lost documents simple, secure, and effective.</p>

        </div>
        <div class="flex items-center justify-center gap-10 w-full flex-wrap">
            <div class="flex p-5 flex-col items-start justify-center gap-4 shadow-md  bg-white rounded-md">
                <div class="items-center bg-gray-100 p-2 justify-center rounded-md flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-image text-lightBlue" data-lov-id="src/components/Features.tsx:24:10"
                        data-lov-name="ImageIcon" data-component-path="src/components/Features.tsx"
                        data-component-line="24" data-component-file="Features.tsx" data-component-name="ImageIcon"
                        data-component-content="%7B%22className%22%3A%22text-lightBlue%22%7D">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                        <circle cx="9" cy="9" r="2"></circle>
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                    </svg>

                </div>
                <h3 class="text-md font-bold" style="color: #102b48;">Ai Image matching</h3>
                <p class="w-full  text-left" style="max-width: 240px; height: 100px;">Our proprietary AI algorithm
                    identifies and matches documents with
                    99% accuracy.</p>

                <div class="flex flex-col items-start gap-2">
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Instant Recognition</p>

                    </div>
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Works with partial images</p>

                    </div>
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Continous Learning</p>

                    </div>

                </div>

            </div>
            <div class="flex p-5 flex-col items-start justify-center gap-4 shadow-md  bg-white rounded-md">
                <div class="items-center bg-gray-100 p-2 justify-center rounded-md flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-map-pin text-lightBlue" data-lov-id="src/components/Features.tsx:31:10"
                        data-lov-name="MapPin" data-component-path="src/components/Features.tsx"
                        data-component-line="31" data-component-file="Features.tsx" data-component-name="MapPin"
                        data-component-content="%7B%22className%22%3A%22text-lightBlue%22%7D">
                        <path
                            d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0">
                        </path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>

                </div>
                <h3 class="text-md font-bold" style="color: #102b48;">Geo Location Based Matching</h3>
                <p class="w-full  text-left" style="max-width: 240px; height: 100px;">Location-aware matching that
                    prioritizes results within your
                    geographical area.</p>

                <div class="flex flex-col items-start gap-2">
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Location Filtering</p>

                    </div>
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Area based notifications</p>

                    </div>
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Map integration</p>

                    </div>

                </div>

            </div>
            <div class="flex p-5 flex-col items-start justify-center gap-4 shadow-md  bg-white rounded-md">
                <div class="items-center bg-gray-100 p-2 justify-center rounded-md flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-lock text-lightBlue" data-lov-id="src/components/Features.tsx:38:10"
                        data-lov-name="Lock" data-component-path="src/components/Features.tsx" data-component-line="38"
                        data-component-file="Features.tsx" data-component-name="Lock"
                        data-component-content="%7B%22className%22%3A%22text-lightBlue%22%7D">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>

                </div>
                <h3 class="text-md font-bold" style="color: #102b48;">Secure Verification Process</h3>
                <p class="w-full  text-left" style="max-width: 240px; height: 100px;">Multi-layer verification system to
                    ensure documents return to
                    legitimate owners.</p>

                <div class="flex flex-col items-start gap-2">
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Identity verification</p>

                    </div>
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Encrypted Communication</p>

                    </div>
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Secure handling</p>

                    </div>

                </div>

            </div>
            <div class="flex p-5 flex-col items-start justify-center gap-4 shadow-md  bg-white rounded-md">
                <div class="items-center bg-gray-100 p-2 justify-center rounded-md flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-users text-lightBlue" data-lov-id="src/components/Features.tsx:45:10"
                        data-lov-name="Users" data-component-path="src/components/Features.tsx" data-component-line="45"
                        data-component-file="Features.tsx" data-component-name="Users"
                        data-component-content="%7B%22className%22%3A%22text-lightBlue%22%7D">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>

                </div>
                <h3 class="text-md font-bold" style="color: #102b48;">Community Support Network</h3>
                <p class="w-full  text-left" style="max-width: 240px; height: 100px;">Join thousands of community
                    members helping reconnect people with
                    lost documents.</p>

                <div class="flex flex-col items-start gap-2">
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Active Community</p>

                    </div>
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">Local Volunteer network</p>

                    </div>
                    <div class="flex gap-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-circle-check-big text-lightBlue mt-0.5 flex-shrink-0"
                            data-lov-id="src/components/Features.tsx:112:22" data-lov-name="CheckCircle"
                            data-component-path="src/components/Features.tsx" data-component-line="112"
                            data-component-file="Features.tsx" data-component-name="CheckCircle"
                            data-component-content="%7B%22className%22%3A%22text-lightBlue%20mt-0.5%20flex-shrink-0%22%7D">
                            <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                            <path d="m9 11 3 3L22 4"></path>
                        </svg>
                        <p class="text-sm">success stories</p>

                    </div>

                </div>

            </div>


        </div>
    </div>
    <div class="flex flex-col items-center justify-center px-20 py-10" style="background-color: #102b48;">
        <div class="flex flex-col gap-7 items-center justify-center bg-gray-100 p-5 rounded-lg">
            <div class="flex flex-col gap-6 items-center justify-center">
                <div class="bg-blue-200 items-center justify-center px-6 py-2 rounded-full">
                    <p class="text-blue-600 font-semibold text-md">Key Features</p>

                </div>
                <h1 style="color: #102b48;" class="text-4xl font-bold text-center">Try Lostify's AI Scanner Now</h1>
                <p class="text-md w-full max-w-xl text-center text-gray-500 ">Our cutting-edge technology and thoughtful
                    features make
                    recovering lost documents simple, secure, and effective.</p>

            </div>
            <div class="w-full rounded-md flex flex-col gap-5 items-center justify-center p-5"
                style="border: 1.5px dashed blue;" onclick="document.getElementById('fileassignment').click()">

                <div
                    class="flex p-5 items-center justify-center rounded-full bg-white shadow-lg hover:shadow-xl hover:transition duration-500 hover:bottom-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-upload text-lightBlue">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" x2="12" y1="3" y2="15"></line>
                    </svg>
                </div>

                <p class="text-sm text-gray-500">Drag and drop your document here, or click to select a file</p>

                <span class="bg-blue-500 px-6 py-4 rounded-md text-white hover:bg-blue-700 transition-colors">
                    Scan Document Now
                </span>

                <input type="file" id="fileassignment" name="assignmentFile" class="hidden"
                    onchange="handleFileChange(event)" multiple />
                <p class="text-sm text-gray-500">Our Ai will automatically search for matches</p>
            </div>
            <div class="flex w-full items-center justify-between flex-wrap">
                <div class="flex items-start justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-lock text-lightBlue" data-lov-id="src/components/Features.tsx:38:10"
                        data-lov-name="Lock" data-component-path="src/components/Features.tsx" data-component-line="38"
                        data-component-file="Features.tsx" data-component-name="Lock"
                        data-component-content="%7B%22className%22%3A%22text-lightBlue%22%7D">
                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <p class="text-sm text-gray-600">All uploads are encrypted and secure</p>

                </div>
                <div class="flex items-center justify-center gap-1">

                    <p class="text-sm text-gray-600">Supported formats:</p>
                    <div
                        class="flex bg-gray-300 items-center justify-center px-2 py-1 rounded-md text-gray-600 text-sm">
                        JPG

                    </div>
                    <div
                        class="flex bg-gray-300 items-center justify-center px-2 py-1 rounded-md text-gray-600 text-sm">
                        PNG

                    </div>
                    <div
                        class="flex bg-gray-300 items-center justify-center px-2 py-1 rounded-md text-gray-600 text-sm">
                        PDF

                    </div>

                </div>

            </div>



        </div>

    </div>
    <div class="px-20 py-20 w-full flex flex-col items-center justify-center gap-20">
        <div class="flex w-full flex-col gap-8 items-center justify-center">
            <div class="bg-blue-200 items-center justify-center px-6 py-2 rounded-full">
                <p class="text-blue-600 font-semibold text-md">success stories</p>

            </div>
            <h1 style="color: #102b48;" class="text-4xl font-bold text-center">What they say about us</h1>
            <p class="text-md w-full max-w-2xl text-center ">Hear from people who have successfully reunited with their lost documents through Lostify</p>
            <div class="flex flex-row flex-wrap items-center justify-between w-full py-5">
            <div class="flex flex-col p-5 rounded-md items-start justify-center shadow-lg gap-2">
                <div class="flex  p-2 items-center  justify-center rounded-full">
                    <img src="./assets/images/person1.jpg" alt="" class="w-10 h-10 rounded-full">
                </div>
              
                <p style="w-full max-w-xl text-sm">I lost my document while travelling to Rwanda but when i used lostify i was able to find it</p>
                <p class="text-md" style="color: #102b48;">Dushimire Aine</p>

            </div>
            <div class="flex flex-col p-5 rounded-md items-start justify-center shadow-lg gap-2">
                <div class="flex  p-2 items-center  justify-center rounded-full">
                    <img src="./assets/images/person1.jpg" alt="" class="w-10 h-10 rounded-full">
                </div>
              
                <p style="w-full max-w-xl text-sm">I lost my document while travelling to Rwanda but when i used lostify i was able to find it</p>
                <p class="text-md" style="color: #102b48;">Mugabo Mike</p>

            </div>

            </div>

        </div>
        </div>
        <?php 
        include("./includes/footer.php")
        ?>

    <script src="./assets/js/home.js"></script>
</body>

</html>