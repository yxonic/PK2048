#!/usr/bin/python3
# -*- coding: utf-8 -*-

import sys
import os
import subprocess
import time
from aigame import *
from pkjudge import *

def print_usage():
    print('Usage: python PK2048.py <directory> <bot1> <bot2>')

if len(sys.argv) <= 3:
    print_usage()
    exit(-1)

# Bot names
bot1 = sys.argv[2]
bot2 = sys.argv[3]
# Working directorys
home_dir = sys.argv[1]
src_dir = os.path.join(home_dir, 'src/')
bin_dir = os.path.join(home_dir, 'bin/')
log_dir = os.path.join(home_dir, 'log/')
if not os.path.isdir(home_dir):
    print('Invalid working directory.')
    exit(2)
try:
    if not os.path.isdir(bin_dir):
        os.mkdir(bin_dir)
    if not os.path.isdir(log_dir):
        os.mkdir(log_dir)
except FileExistsError:
    print('A messy directory. Please use a clean one.')
    exit(2)
except PermissionError:
    print('Permission denied! Make sure you have access to the ' \
          'working directory.')
    exit(2)
# Files
bot1_src = src_dir + bot1
if not os.path.isfile(bot1_src):
    print('Bot file not found.')
    exit(2)
bot1_bin = bin_dir + bot1 + '.exe'
bot2_src = src_dir + bot2
if not os.path.isfile(bot2_src):
    print('Bot file not found.')
    exit(2)
bot2_bin = bin_dir + bot2 + '.exe'
log_name = str(int(time.time() * 1000)) + '.log'
log_file = log_dir + log_name

'''begin_debug
print(home_dir)
print(src_dir)
print(bin_dir)
end_debug'''

# Compile two bots
##TODO##ADD MULTIPLE STANDARD SUPPORT##
if bot1.endswith('.c'):
    bot1_C = 'gcc'
else:
    bot1_C = 'g++'
if bot2.endswith('.c'):
    bot2_C = 'gcc'
else:
    bot2_C = 'g++'

state = subprocess.call([bot1_C, '-o', bot1_bin, bot1_src])
if state != 0:
    print('Compilation failed!')
    exit(3)
state = subprocess.call([bot2_C, '-o', bot2_bin, bot2_src])
if state != 0:
    print('Compilation failed!')
    exit(4)

p1 = PKPlayer(bot1, bot1_bin)
p2 = PKPlayer(bot2, bot2_bin)
logger = Logger(log_file)
game = Game(p1, p2, logger)
game.start()
print(log_name)
