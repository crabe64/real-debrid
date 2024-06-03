import yt_dlp

# URL de la playlist YouTube Music (remplacez ceci par l'URL de votre playlist)
playlist_url = 'https://www.youtube.com/playlist?list=PLVR3ODIv8N63qILCPqUthDF6obdtypHno'

ydl_opts = {
    'quiet': True,
    'extract_flat': True,
    'skip_download': True,
}

with yt_dlp.YoutubeDL(ydl_opts) as ydl:
    info_dict = ydl.extract_info(playlist_url, download=False)
    playlist_title = info_dict.get('title', None)
    print(f'Playlist: {playlist_title}')
    for entry in info_dict['entries']:
        print(f"https://www.youtube.com/watch?v={entry['id']}")
