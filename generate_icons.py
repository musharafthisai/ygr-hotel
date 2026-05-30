#!/usr/bin/env python3
"""Generate PWA icons for YGR signature."""
import os, sys, io, math
from PIL import Image, ImageDraw, ImageFont

# Force UTF-8 output
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

OUT_DIR = os.path.join(os.path.dirname(__file__), "assets", "img", "icons")
os.makedirs(OUT_DIR, exist_ok=True)

# Colors
DARK_GREEN = (13, 40, 24)
LIGHT_GREEN = (30, 123, 75)
WHITE = (255, 255, 255)
GOLD = (212, 175, 55)

def create_icon(size):
    img = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    draw = ImageDraw.Draw(img)

    # Rounded rect background
    r = size // 5
    draw.rounded_rectangle([(0, 0), (size - 1, size - 1)], radius=r, fill=DARK_GREEN)

    # Gradient overlay
    for y in range(size):
        alpha = int(60 * (1 - y / size))
        draw.line([(0, y), (size, y)], fill=(LIGHT_GREEN[0], LIGHT_GREEN[1], LIGHT_GREEN[2], alpha))

    # Bowl shape
    cx, cy = size // 2, size // 2 - size // 20
    bowl_w = size * 3 // 5
    bowl_h = size * 2 // 5
    bbox = [cx - bowl_w // 2, cy - bowl_h // 4, cx + bowl_w // 2, cy + bowl_h * 3 // 4]
    draw.pieslice(bbox, start=0, end=180, fill=WHITE, outline=None)

    # Bowl rim
    rim_y = cy - bowl_h // 4
    draw.arc([cx - bowl_w // 2 - 2, rim_y - 4, cx + bowl_w // 2 + 2, rim_y + 8],
             start=0, end=180, fill=(220, 220, 220), width=max(3, size // 40))

    # Steam
    for i, (sx, sway) in enumerate([(cx - bowl_w // 6, -size // 12),
                                      (cx + bowl_w // 8, -size // 10),
                                      (cx, -size // 8)]):
        sy = rim_y + sway
        sw = max(2, size // 30)
        for j in range(3):
            t = j / 3
            x = sx + int(math.sin(t * math.pi) * size // 16)
            y = sy - int(t * size // 5)
            alpha_layer = int(120 * (1 - t))
            draw.ellipse([(x - sw, y - sw), (x + sw, y + sw)],
                         fill=(255, 255, 255, alpha_layer))

    # YGR text
    font_size = max(10, size // 8)
    try:
        font = ImageFont.truetype("C:\\Windows\\Fonts\\segoeuib.ttf", font_size)
    except:
        try:
            font = ImageFont.truetype("C:\\Windows\\Fonts\\arialbd.ttf", font_size)
        except:
            font = ImageFont.load_default()

    text = "YGR"
    bbox_text = draw.textbbox((0, 0), text, font=font)
    text_w = bbox_text[2] - bbox_text[0]
    text_h = bbox_text[3] - bbox_text[1]
    tx = (size - text_w) // 2
    ty = cy + bowl_h * 3 // 4 + size // 16

    draw.text((tx + 1, ty + 1), text, font=font, fill=(0, 0, 0, 60))
    draw.text((tx, ty), text, font=font, fill=GOLD)

    # Save
    path = os.path.join(OUT_DIR, f"icon-{size}.png")
    img.save(path, "PNG")
    print(f"[OK] Created {path} ({size}x{size})")
    return path

create_icon(192)
create_icon(512)
print("[OK] All icons generated successfully!")
