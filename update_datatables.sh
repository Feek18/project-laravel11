#!/bin/bash

# Files to update
files=(
    "resources/views/components/admin/matkul.blade.php"
    "resources/views/components/admin/jadwal.blade.php"
    "resources/views/components/admin/akun.blade.php"
    "resources/views/components/admin/peminjam.blade.php"
    "resources/views/components/user/pages/dashboard/pesanan.blade.php"
)

# CSS replacement pattern
old_css_pattern='<!-- DataTables CSS -->'
new_css_block='<!-- DataTables Custom CSS -->
    <link rel="stylesheet" href="{{ asset('\''css/datatables-custom.css'\'') }}">'

# Process each file
for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo "Updating $file..."
        
        # Replace CSS links
        sed -i 's|<link rel="stylesheet" href="https://cdn.datatables.net/.*\.css">||g' "$file"
        sed -i 's|<script src="https://cdn.datatables.net/.*dataTables.tailwindcss.*\.js"></script>||g' "$file"
        
        # Add our custom CSS
        sed -i 's|<!-- DataTables CSS -->|<!-- DataTables Custom CSS -->\n    <link rel="stylesheet" href="{{ asset('\''css/datatables-custom.css'\'') }}">|g' "$file"
        
        echo "Updated $file"
    else
        echo "File not found: $file"
    fi
done

echo "All files updated!"
