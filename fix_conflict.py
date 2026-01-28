
file_path = '/home/angger/Project/web-gis-esdm-listrik/resources/views/admin/permohonan-user/show.blade.php'

with open(file_path, 'r') as f:
    lines = f.readlines()

# 1-based line numbers to remove
# Range 531 to 816 (inclusive)
# Line 824

new_lines = []
for i, line in enumerate(lines):
    line_num = i + 1
    if 531 <= line_num <= 816:
        continue
    if line_num == 824:
        continue
    new_lines.append(line)

with open(file_path, 'w') as f:
    f.writelines(new_lines)

print(f"Fixed {len(lines)} lines to {len(new_lines)} lines.")
