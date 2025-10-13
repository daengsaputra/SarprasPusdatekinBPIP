<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dark Mode Laravel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // Jalankan seawal mungkin untuk auto deteksi sistem
    (() => {
      const storedTheme = localStorage.getItem('theme');
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
        document.documentElement.classList.add('dark');
      }
    })();
  </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 flex flex-col items-center justify-center min-h-screen transition-all duration-500">
  <!-- Tombol -->
  <button id="toggle-theme"
    class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-300">
    <svg id="icon-sun" class="w-5 h-5 text-yellow-500 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
      <path d="M10 2a.75.75 0 01.75.75V4a.75.75 0 01-1.5 0V2.75A.75.75 0 0110 2zM10 16a.75.75 0 01.75.75V18a.75.75 0 01-1.5 0v-1.25A.75.75 0 0110 16zm8-6a.75.75 0 01.75.75H18a.75.75 0 010-1.5h.75A.75.75 0 0118 10zM2 10a.75.75 0 01.75.75H4a.75.75 0 010-1.5H2.75A.75.75 0 012 10zm12.657 4.657a.75.75 0 011.06 1.06l-.884.884a.75.75 0 11-1.06-1.06l.884-.884zM4.227 4.227a.75.75 0 011.06 0l.884.884a.75.75 0 11-1.06 1.06l-.884-.884a.75.75 0 010-1.06zm9.374-1.06a.75.75 0 010 1.06l-.884.884a.75.75 0 01-1.06-1.06l.884-.884a.75.75 0 011.06 0zM5.17 14.83a.75.75 0 010 1.06l-.884.884a.75.75 0 11-1.06-1.06l.884-.884a.75.7
