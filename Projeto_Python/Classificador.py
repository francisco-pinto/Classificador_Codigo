# -*- coding: utf-8 -*-
"""
Created on Thu Jun  3 14:44:44 2021

@author: ffcpi
"""

import MySQLdb
import patoolib
import os
from ctypes import *
import subprocess

uploadURL = "C:/wamp64/www/Projeto/Uploads"
ExtractedFilesURL = "C:/wamp64/www/Projeto/Projeto_Python/Extracted Files"


servername = "127.0.0.1"
databaseName = "Classificador_Codigo"
username = "root"
password = ""


#Connect DB

db = MySQLdb.connect(host=servername,    # your host, usually localhost
                     user=username,         # your username
                     passwd=password,  # your password
                     db=databaseName)        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()


#File Path from the uploaded files
filesPath = uploadURL


#Programa corre infintamente
#é sempre corrigido o primeiro ficheiro
#das pastas zipadas e depois é retirado
#destas e colocado o ficheiro final noutra pasta



while True: 
    #Get file names
    files = []
    for (dirpath, dirnames, filenames) in os.walk(filesPath):
        files.extend(filenames)
        break
        
    print(files[0])
        
    
    #Get project ID to get the casos_Teste
    cur.execute("SELECT ProjetoID FROM ficheiro where Nome=%s", (files[0], ))
    
    for row in cur.fetchall():
        print("Projeto ID: ", row[0])
        projectID = row[0]
    
    
    
    
    
    #Get Casos_Teste
    cur.execute("SELECT Output FROM casos_teste where ProjetoID=%s", (projectID, ))
    Ouputs = []
    for row in cur.fetchall():
        Ouputs.extend(row)
    
    #print("\n\nOutputs: ", Ouputs)
        
    cur.execute("SELECT Input FROM casos_teste where ProjetoID=%s", (projectID, ))
    Inputs = []
    for row in cur.fetchall():
        Inputs.extend(row)
    
    #print("\n\nInputs: ", Inputs)
    db.close()
    
    
    
    
    
    
    #Extract the file
    fileToExtract = filesPath + "/" + files[0]
    
    #Extract file
    try:
        patoolib.extract_archive(fileToExtract, outdir=ExtractedFilesURL) 
    except:
        #Means that a file already exists there 
        if os.path.exists(ExtractedFilesURL):
            os.remove(ExtractedFilesURL)

        
    
    #Get Extracted file names
    ExtractedFiles = []
    for (dirpath, dirnames, filenames) in os.walk(ExtractedFilesURL):
        ExtractedFiles.extend(filenames)
        break
    
    print("\n\n", ExtractedFiles)
    
    
        

        
    
    
    # #Execute the file
    fileToExecute = ExtractedFilesURL + "/" + ExtractedFiles[0];
    
    print("Path do ficheiro a executar: ", fileToExecute)
    
    





#Problema atual. Não conseguimos executar o ficheiro .C
    subprocess.call(["gcc", fileToExecute], shell=True)
    subprocess.call("./a.out", shell=True)

    # my_functions = CDLL(fileToExecute)
    
    # print(type(my_functions))
    # print(my_functions.square(2, 2))
