<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-900 py-4">
           
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-center pt-8 justify-center">
                   <h2 class="text-gray-300 font-bold text-2xl">PDF Table of Contents Hyperlinking Tool</h2>
                </div>

                <div class="mt-8 bg-white overflow-hidden shadow rounded-lg">
                    <div class="grid grid-cols-1">
                        <div class="p-6 border-t min-w-[600px] bg-gray-800 border-gray-700">
                            <form action="{{ url('analysis-pdf') }}" method="post">
                                {{ csrf_field() }}
                                <div class="mt-2 text-gray-400 text-sm">
                                    Link to a PDF document located on Google Cloud storage
                                    <div class="mb-4 mt-2">
                                        <input 
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                            id="file" 
                                            type="url" 
                                            name="file"
                                            placeholder="https://storage.googleapis.com ..."
                                            value="{{ old('file') }}"
                                            required
                                        />
                                    </div>
                                    @if ($errors->any())
                                        <div class="mb-2">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li class="text-red-400 text-sm">{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if (session('success'))
                                        <div class="mb-2">
                                            <span class="text-green-400 text-sm">{{ session('success') }}</span>
                                        </div>
                                    @endif
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Submit
                                    </button>
                                    @if (session('success'))
                                        <button type="button" onClick="copyToClipboard('{{ session('file') }}')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Copy URL to clipboard
                                        </button>
                                        <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Upload to Google Drive
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            (function(w, d) {
                w.addEventListener('load', init, false);

                function init() {
                    // ... init
                }

                w.copyToClipboard = function(string) {
                    const el = document.createElement('textarea');
                    el.value = string;
                    el.setAttribute('readonly', '');
                    el.style.position = 'absolute';
                    el.style.left = '-9999px';
                    document.body.appendChild(el);
                    el.select();
                    document.execCommand('copy');
                    document.body.removeChild(el);

                    alert('Copied to clipboard!')
                }
            })(window, document);
        </script>
    </body>
</html>
