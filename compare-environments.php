<?php
echo "🔍 EXPLORANDO ESTRUCTURA REAL DEL VENDOR<br><br>";

$vendorPath = __DIR__ . '/vendor';

// 1. Verificar autoload real
echo "1. 📄 AUTOLOAD REAL:<br>";
$autoloadContent = file_get_contents($vendorPath . '/autoload.php');
if (strpos($autoloadContent, 'ComposerAutoloaderInit') !== false) {
    echo "✅ Autoloader real de Composer<br>";
    
    // Encontrar el namespace del autoloader
    preg_match('/class (ComposerAutoloaderInit[^\s]+)/', $autoloadContent, $matches);
    if (isset($matches[1])) {
        echo "&nbsp;&nbsp;↳ Clase autoloader: " . $matches[1] . "<br>";
    }
} else {
    echo "❌ No es autoloader de Composer<br>";
}

// 2. Explorar estructura de illuminate
echo "<br>2. 📁 ESTRUCTURA DE ILLUMINATE:<br>";
$illuminatePath = $vendorPath . '/illuminate';

if (file_exists($illuminatePath)) {
    $items = scandir($illuminatePath);
    foreach ($items as $item) {
        if ($item != '.' && $item != '..' && is_dir($illuminatePath . '/' . $item)) {
            echo "&nbsp;&nbsp;📁 $item/<br>";
            
            // Mostrar algunos archivos importantes
            $subItems = scandir($illuminatePath . '/' . $item);
            $fileCount = 0;
            foreach ($subItems as $subItem) {
                if ($subItem != '.' && $subItem != '..' && is_file($illuminatePath . '/' . $item . '/' . $subItem)) {
                    if ($fileCount < 3) { // Mostrar solo 3 archivos por carpeta
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;📄 $subItem<br>";
                    }
                    $fileCount++;
                }
            }
            if ($fileCount > 3) {
                echo "&nbsp;&nbsp;&nbsp;&nbsp;... y " . ($fileCount - 3) . " archivos más<br>";
            }
        }
    }
} else {
    echo "❌ No existe illuminate/<br>";
}

// 3. Buscar Application.php en diferentes ubicaciones
echo "<br>3. 🔍 BUSCANDO APPLICATION.PHP:<br>";
$possibleAppPaths = [
    '/illuminate/foundation/Application.php',
    '/illuminate/foundation/application.php', 
    '/illuminate/Foundation/Application.php',
    '/laravel/framework/src/Illuminate/Foundation/Application.php'
];

foreach ($possibleAppPaths as $path) {
    $fullPath = $vendorPath . $path;
    if (file_exists($fullPath)) {
        echo "✅ APPLICATION ENCONTRADO: $path<br>";
        break;
    }
}

// 4. Test de funcionamiento
echo "<br>4. 🧪 TEST DE FUNCIONAMIENTO:<br>";
require_once $vendorPath . '/autoload.php';

// Probar reflection para encontrar la ubicación real
try {
    $reflection = new ReflectionClass('Illuminate\Foundation\Application');
    echo "✅ Illuminate\Foundation\Application ubicación real: " . $reflection->getFileName() . "<br>";
} catch (Exception $e) {
    echo "❌ No se pudo obtener ubicación: " . $e->getMessage() . "<br>";
}

// 5. Crear instrucciones específicas
echo "<br>5. 🎯 INSTRUCCIONES ESPECÍFICAS:<br>";

// Obtener estructura real para las instrucciones
$realStructure = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($vendorPath, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $file) {
    if ($file->isFile()) {
        $relativePath = str_replace($vendorPath . '\\', '', $file->getPathname());
        $realStructure[] = $relativePath;
    }
}

// Guardar estructura real
file_put_contents('vendor-real-structure.txt', implode("\n", $realStructure));
echo "✅ Estructura real guardada: vendor-real-structure.txt (" . count($realStructure) . " archivos)<br>";

echo "<br>🎉 EXPLORACIÓN COMPLETADA<br>";
?>