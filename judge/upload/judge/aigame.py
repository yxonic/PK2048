#!/usr/bin/python3
# -*- coding: utf-8 -*-

import subprocess
import select

class Player:
    def __init__(self, name, binfile, timeout=1.0):
        self.__name = name
        self.__binfile = binfile
        self.__timeout = timeout

    def start(self, init_msg):
        self.__score = 0
        self.__proc = subprocess.Popen([self.__binfile], \
                                       universal_newlines=True, \
                                       stdin=subprocess.PIPE, \
                                       stdout=subprocess.PIPE)
        self.__proc.stdin.write(str(init_msg) + '\n')
        
    def query(self, message):
        if (self.__proc.poll()):
            raise Exception('Unexpectedly down!')
        self.__proc.stdin.write(str(message) + '\n')
        self.__proc.stdin.flush()
        out, _, _ = select.select([self.__proc.stdout], [], [], \
                                  self.__timeout)
        if not out:
            raise Exception('Timeout!')
        return out[0].readline()

    def gain(self, score):
        self.__score += score

    def score(self):
        return self.__score

    def terminate(self):
        self.__proc.terminate()
        return self.__score

    def name(self):
        return self.__name

class Logger:
    DEBUG, INFO, ERROR = range(3)
    def __init__(self, file_name, level=INFO):
        self.__name = file_name
        self.__flow = open(file_name, 'w')
        self.__level = level
    def log(self, msg, level=INFO):
        if (level >= self.__level):
            self.__flow.write(str(level) + ': ' + str(msg) + '\n')
    def close(self):
        self.__flow.close()
