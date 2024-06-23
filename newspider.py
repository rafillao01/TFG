import os
import sys
import scrapy
from urllib.parse import urlparse
from bs4 import BeautifulSoup

class MySpider(scrapy.Spider):
    name = 'myspider'
    MAX_PAGES_PER_COMPANY = 200  
    downloaded_files = {}

    def __init__(self, *args, **kwargs):
        super(MySpider, self).__init__(*args, **kwargs)
        self.start_urls = [kwargs.get('url')] if 'url' in kwargs else []

    def parse(self, response):
        company_name = urlparse(response.url).hostname.split('.')[1]

        if company_name not in self.downloaded_files:
            self.downloaded_files[company_name] = set()

        if len(self.downloaded_files[company_name]) >= self.MAX_PAGES_PER_COMPANY:
            return

        output_dir = os.path.join('/var/TFG/ProyectoSostenibilidad/resultados', company_name)
        if not os.path.exists(output_dir):
            os.makedirs(output_dir)
        output_files = [f for f in os.listdir(output_dir) if f.startswith('archivo')]
        file_index = len(output_files) + 1

        extension = os.path.splitext(response.url)[1]
        if not extension:
            extension = '.html'

        output_filename = f'archivo{file_index}{extension}'
        output_path = os.path.join(output_dir, output_filename)

        if response.url not in self.downloaded_files[company_name]:
            with open(output_path, 'wb') as f:
                f.write(response.body)
            self.downloaded_files[company_name].add(response.url)
            self.log(f'Descargado: {response.url} para {company_name}')
            
            if extension == '.html' or (not extension and self.is_html(response.body)):
                new_output_path = os.path.join(output_dir, f'archivo{file_index}.html')
                os.rename(output_path, new_output_path)

            with open(os.path.join(output_dir, f'{company_name}.csv'), 'a') as csv_file:
                filename = f'archivo{file_index}{extension}'
                csv_file.write(f'{filename}: {response.url}\n')

        links = response.css('a::attr(href)').extract()
        for link in links:
            url = response.urljoin(link)
            parsed_url = urlparse(url)
            if parsed_url.netloc == urlparse(response.url).netloc:
                yield scrapy.Request(url=url, callback=self.parse)

    def start_requests(self):
        for url in self.start_urls:
            if not urlparse(url).scheme:
                url = "http://" + url
            yield scrapy.Request(url, callback=self.parse)

    def is_html(self, content):
        soup = BeautifulSoup(content, 'html.parser')
        return len(soup.find_all()) > 0