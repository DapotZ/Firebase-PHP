<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temperature Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* CSS untuk gradient background */
        .bg-blue-gradient {
            background-image: linear-gradient(to bottom, #50BCE9, #5A6CDD);
        }

        .bg-black-gradient {
            background-image: linear-gradient(to bottom, #E0A1E8, #F79D82);
        }
    </style>
</head>

<body class="bg-blue-gradient">
    <div class="flex flex-col w-full h-screen justify-around">
        <div class="flex justify-center items-center gap-3 text-white">
            <img src="assets/images/iconlogo.png" class="w-20" alt="Logo">
            <h2 class="font-bold text-5xl" style="font-family: 'Rubik', sans-serif">QUICK TEMP</h2>
        </div>

        <div class="slide">
            <div class="flex flex-col justify-center items-center gap-10 text-white">
                <?php if ($temperature) : ?>
                    <?php $temp = $temperature->temperature; ?>
                    <p id="temperature" class="text-7xl font-bold" style="font-family: 'Rubik', sans-serif"><?php echo $temp; ?>°C</p>

                    <?php if ($healthStatus) : ?>
                        <img src="<?php echo $healthStatus['image']; ?>" class="w-24" alt="Health Status">
                        <p id="healthStatus" class="text-2xl"><?php echo $healthStatus['text']; ?></p>
                    <?php endif; ?>

                <?php else : ?>
                    <p>No temperature data available.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="slide hidden">
            <div class="flex flex-col justify-center items-center gap-10 text-white">
                <?php if ($temperature) : ?>
                    <?php $temp = $temperature->temperature; ?>
                    <p id="temperatureF" class="text-7xl font-bold" style="font-family: 'Rubik', sans-serif"><?php echo (($temp * 9 / 5) + 32); ?>°F</p>

                    <?php if ($healthStatus) : ?>
                        <img src="<?php echo $healthStatus['image']; ?>" class="w-24" alt="Health Status">
                        <p id="healthStatus" class="text-2xl"><?php echo $healthStatus['text']; ?></p>
                    <?php endif; ?>

                <?php else : ?>
                    <p>No temperature data available.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex flex-col justify-center items-center text-white">
            <p id="day" class="text-lg"><?php echo date('l'); ?></p>
            <p id="date" class="text-lg"><?php echo date('d/m/Y'); ?></p>
        </div>

        <div class="absolute flex items-center justify-between w-full px-5 text-white text-3xl">
            <i id="prevSlide" class="fas fa-chevron-left cursor-pointer"></i>
            <i id="nextSlide" class="fas fa-chevron-right cursor-pointer"></i>
        </div>
    </div>

    <div class="temps">
        <?php foreach ($firestoreData as $key => $data) : ?>
            <?php $healthStatus = $healthStatusFirestore[$key]; ?>
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <article class="hover:animate-background rounded-xl bg-gradient-to-r from-blue-500 via-blue-500 to-purple-600 p-0.5 shadow-xl transition hover:bg-[length:400%_400%] hover:shadow-sm hover:[animation-duration:_4s]">
                    <div class="rounded-[10px] bg-white p-4 sm:p-6 flex gap-10">
                        <img src="<?php echo $healthStatus['image']; ?>" class="w-28" alt="Health Status">
                        <div class="flex flex-col justify-between w-full">
                            <div>
                                <h3 class="mt-3 text-4xl font-bold text-gray-900" style="font-family: 'Rubik', sans-serif">
                                    <?php echo $data->getValue(); ?>°C
                                </h3>

                                <div class="mt-4">
                                    <span class="whitespace-nowrap rounded-full bg-purple-100 px-2.5 py-0.5 text-xs text-purple-600" style="font-family: 'Rubik', sans-serif">
                                        <?php echo $healthStatus['text']; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="self-end">
                                <span class="whitespace-nowrap rounded-full bg-purple-100 px-2.5 text-xs text-purple-600" style="font-family: 'Rubik', sans-serif">
                                    <?php echo date('Y-m-d', strtotime($data->getTimestamp())); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        <?php endforeach; ?>

        <div class="flex justify-center py-6">
            <div class="inline-flex items-center justify-center rounded bg-blue-600 py-1 text-white">
                <?php if ($page > 1) : ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="inline-flex size-8 items-center justify-center rtl:rotate-180">
                        <span class="sr-only">Prev Page</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>

                <span class="h-4 w-px bg-white/25" aria-hidden="true"></span>

                <div>
                    <label for="PaginationPage" class="sr-only"><?php echo $page; ?> </label>

                    <input type="number" class="h-8 w-12 rounded border-none bg-transparent p-0 text-center text-xs font-medium [-moz-appearance:_textfield] focus:outline-none focus:ring-inset focus:ring-white [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none" min="1" id="PaginationPage" value="<?php echo $page; ?>" />
                </div>

                <span class="h-4 w-px bg-white/25"></span>

                <?php if ($page < $totalPages) : ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="inline-flex size-8 items-center justify-center rtl:rotate-180">
                        <span class="sr-only">Next Page</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="js/index.js"></script>
</body>

</html>