import requests
import re


def getVideoLink(reel_link):
    reel_id = re.findall(r'reel\/(.*?)\/', reel_link)[0]
    link = "https://www.instagram.com/graphql/query/"
    headers = {
        'user-agent': 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36'
    }
    params = {
        'hl': 'en',
        'query_hash': 'b3055c01b4b222b8a47dc12b090e4e64',
        'variables': '{"child_comment_count":3,"fetch_comment_count":40,"has_threaded_comments":true,"parent_comment_count":24,"shortcode":"' + reel_id + '"}'
    }
    try:
        resp = requests.get(link, headers=headers, params=params).json()
    except:
        print("Failed to open {}".format(link))
        return
    video_link = resp['data']['shortcode_media']['video_url']
    downloadReel(video_link, reel_id)


def downloadReel(video_link, reel_id):
    print("Downloading reel from {} as {}.mp4".format(video_link, reel_id))
    headers = {
        'user-agent': 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36'
    }
    try:
        resp = requests.get(video_link, headers=headers).content
    except:
        print("Failed to open {}".format(video_link))
        return
    open(f'{reel_id}.mp4', mode='wb').write(resp)
    print("Reel downloaded successfully as {}.mp4".format(reel_id))


if __name__ == "__main__":
    # reel_link = "https://www.instagram.com/reel/CvKqTbLRu9s/?hl=en"
    reel_link = input("Enter an Instagram reels link: ")
    getVideoLink(reel_link)
